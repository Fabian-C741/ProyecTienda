<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = $this->getOrCreateCart($request);

        return response()->json([
            'cart' => $cart->load('items.product'),
            'total' => $cart->total,
            'items_count' => $cart->items_count,
        ]);
    }

    public function addItem(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'variant' => 'nullable|array',
        ]);

        $product = Product::findOrFail($request->product_id);

        if (!$product->isInStock()) {
            return response()->json([
                'message' => 'Producto sin stock disponible',
            ], 400);
        }

        $cart = $this->getOrCreateCart($request);

        // Verificar si el item ya existe en el carrito
        $cartItem = $cart->items()
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $request->quantity);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'variant' => $request->variant,
                'price' => $product->price,
            ]);
        }

        return response()->json([
            'message' => 'Producto agregado al carrito',
            'cart' => $cart->load('items.product'),
        ]);
    }

    public function updateItem(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = $this->getOrCreateCart($request);
        $cartItem = $cart->items()->findOrFail($itemId);

        $cartItem->update([
            'quantity' => $request->quantity,
        ]);

        return response()->json([
            'message' => 'Cantidad actualizada',
            'cart' => $cart->load('items.product'),
        ]);
    }

    public function removeItem(Request $request, $itemId)
    {
        $cart = $this->getOrCreateCart($request);
        $cartItem = $cart->items()->findOrFail($itemId);

        $cartItem->delete();

        return response()->json([
            'message' => 'Producto eliminado del carrito',
            'cart' => $cart->load('items.product'),
        ]);
    }

    public function clear(Request $request)
    {
        $cart = $this->getOrCreateCart($request);
        $cart->items()->delete();

        return response()->json([
            'message' => 'Carrito vaciado',
        ]);
    }

    private function getOrCreateCart(Request $request)
    {
        $user = $request->user();
        $tenantId = $request->header('X-Tenant-ID') ?? $request->get('tenant_id');
        
        if ($user) {
            $cart = Cart::firstOrCreate([
                'user_id' => $user->id,
                'tenant_id' => $tenantId,
            ]);
        } else {
            $sessionId = $request->session()->getId() ?? Str::uuid();
            $cart = Cart::firstOrCreate([
                'session_id' => $sessionId,
                'tenant_id' => $tenantId,
            ]);
        }

        return $cart;
    }
}
