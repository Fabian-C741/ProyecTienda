<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\Order;
use App\Models\ReviewResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    /**
     * Listar reseñas de un producto
     */
    public function index(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);

        $reviews = ProductReview::where('product_id', $productId)
            ->approved()
            ->with(['user:id,name', 'responses.user:id,name'])
            ->when($request->rating, function ($query) use ($request) {
                $query->rating($request->rating);
            })
            ->when($request->verified, function ($query) {
                $query->verified();
            })
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 10);

        return response()->json([
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'average_rating' => ProductReview::averageRating($productId),
                'total_reviews' => ProductReview::where('product_id', $productId)->approved()->count(),
                'rating_distribution' => ProductReview::ratingDistribution($productId),
            ],
            'reviews' => $reviews,
        ]);
    }

    /**
     * Crear una reseña
     */
    public function store(Request $request, $productId)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:200',
            'comment' => 'nullable|string|max:2000',
            'images' => 'nullable|array|max:5',
            'images.*' => 'url',
            'order_id' => 'nullable|exists:orders,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product = Product::findOrFail($productId);
        $user = $request->user();

        // Verificar si ya dejó una reseña
        if (ProductReview::where('product_id', $productId)->where('user_id', $user->id)->exists()) {
            return response()->json([
                'message' => 'Ya has dejado una reseña para este producto'
            ], 422);
        }

        // Verificar si es compra verificada
        $isVerified = false;
        $orderId = $request->order_id;

        if ($orderId) {
            $order = Order::where('id', $orderId)
                ->where('user_id', $user->id)
                ->where('status', 'completed')
                ->whereHas('items', function ($query) use ($productId) {
                    $query->where('product_id', $productId);
                })
                ->first();

            if ($order) {
                $isVerified = true;
            }
        } else {
            // Buscar automáticamente si hay una orden completada
            $order = Order::where('user_id', $user->id)
                ->where('status', 'completed')
                ->whereHas('items', function ($query) use ($productId) {
                    $query->where('product_id', $productId);
                })
                ->first();

            if ($order) {
                $isVerified = true;
                $orderId = $order->id;
            }
        }

        $review = ProductReview::create([
            'product_id' => $productId,
            'user_id' => $user->id,
            'order_id' => $orderId,
            'rating' => $request->rating,
            'title' => $request->title,
            'comment' => $request->comment,
            'images' => $request->images ?? [],
            'is_verified_purchase' => $isVerified,
            'is_approved' => true, // Auto-aprobar (o cambiar a false para moderación)
            'approved_at' => now(),
        ]);

        return response()->json([
            'message' => 'Reseña creada exitosamente',
            'review' => $review->load('user:id,name'),
        ], 201);
    }

    /**
     * Actualizar una reseña
     */
    public function update(Request $request, $productId, $reviewId)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'sometimes|integer|min:1|max:5',
            'title' => 'nullable|string|max:200',
            'comment' => 'nullable|string|max:2000',
            'images' => 'nullable|array|max:5',
            'images.*' => 'url',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $review = ProductReview::where('id', $reviewId)
            ->where('product_id', $productId)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $review->update($request->only(['rating', 'title', 'comment', 'images']));

        return response()->json([
            'message' => 'Reseña actualizada exitosamente',
            'review' => $review->load('user:id,name'),
        ]);
    }

    /**
     * Eliminar una reseña
     */
    public function destroy(Request $request, $productId, $reviewId)
    {
        $review = ProductReview::where('id', $reviewId)
            ->where('product_id', $productId)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $review->delete();

        return response()->json([
            'message' => 'Reseña eliminada exitosamente'
        ]);
    }

    /**
     * Responder a una reseña (vendedor)
     */
    public function respond(Request $request, $productId, $reviewId)
    {
        $validator = Validator::make($request->all(), [
            'response' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $review = ProductReview::where('id', $reviewId)
            ->where('product_id', $productId)
            ->firstOrFail();

        // Verificar que el usuario tenga permisos (vendedor o admin)
        $user = $request->user();
        if (!$user->hasAnyRole(['tenant_admin', 'vendedor', 'super_admin'])) {
            return response()->json([
                'message' => 'No tienes permisos para responder reseñas'
            ], 403);
        }

        $response = ReviewResponse::create([
            'review_id' => $reviewId,
            'user_id' => $user->id,
            'response' => $request->response,
        ]);

        return response()->json([
            'message' => 'Respuesta agregada exitosamente',
            'response' => $response->load('user:id,name'),
        ], 201);
    }

    /**
     * Aprobar/Rechazar reseña (admin)
     */
    public function moderate(Request $request, $reviewId)
    {
        $validator = Validator::make($request->all(), [
            'is_approved' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $review = ProductReview::findOrFail($reviewId);
        
        $review->update([
            'is_approved' => $request->is_approved,
            'approved_at' => $request->is_approved ? now() : null,
            'approved_by' => $request->is_approved ? $request->user()->id : null,
        ]);

        return response()->json([
            'message' => $request->is_approved ? 'Reseña aprobada' : 'Reseña rechazada',
            'review' => $review,
        ]);
    }
}
