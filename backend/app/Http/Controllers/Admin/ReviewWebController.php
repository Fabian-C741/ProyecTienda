<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use Illuminate\Http\Request;

class ReviewWebController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductReview::with(['user', 'product']);

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        $reviews = $query->latest()
            ->paginate(20);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function show(ProductReview $productReview)
    {
        $productReview->load(['user', 'product']);
        return view('admin.reviews.show', compact('productReview'));
    }

    public function destroy(ProductReview $productReview)
    {
        $productReview->delete();

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Reseña eliminada exitosamente');
    }
}
