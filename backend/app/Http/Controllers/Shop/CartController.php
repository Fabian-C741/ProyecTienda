<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = $this->getCart();
        $subtotal = $this->calculateSubtotal($cart);
        $discount = session()->get('cart_discount', 0);
        $total = $subtotal - $discount;

        return view('shop.cart.index', compact('cart', 'subtotal', 'discount', 'total'));
    }

    public function add(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        
        // Verificar stock
        if ($product->stock <= 0 && $product->track_inventory) {
            return back()->with('error', 'Producto sin stock disponible');
        }
        
        $cart = $this->getCart();
        $quantity = $request->input('quantity', 1);
        
        if (isset($cart[$productId])) {
            $newQuantity = $cart[$productId]['quantity'] + $quantity;
            
            // Verificar que no exceda el stock
            if ($product->track_inventory && $newQuantity > $product->stock) {
                return back()->with('error', 'No hay suficiente stock disponible');
            }
            
            $cart[$productId]['quantity'] = $newQuantity;
        } else {
            if ($product->track_inventory && $quantity > $product->stock) {
                return back()->with('error', 'No hay suficiente stock disponible');
            }
            
            $cart[$productId] = [
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => $product->price,
                'quantity' => $quantity,
                'image' => $product->featured_image,
                'tenant_id' => $product->tenant_id,
            ];
        }
        
        session()->put('cart', $cart);
        
        return back()->with('success', 'Producto agregado al carrito');
    }

    public function update(Request $request, $productId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($productId);
        $cart = $this->getCart();
        
        if (isset($cart[$productId])) {
            // Verificar stock
            if ($product->track_inventory && $request->quantity > $product->stock) {
                return back()->with('error', 'No hay suficiente stock disponible');
            }
            
            $cart[$productId]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
            
            return back()->with('success', 'Carrito actualizado');
        }
        
        return back()->with('error', 'Producto no encontrado en el carrito');
    }

    public function remove($productId)
    {
        $cart = $this->getCart();
        
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }
        
        return back()->with('success', 'Producto eliminado del carrito');
    }

    public function clear()
    {
        session()->forget('cart');
        session()->forget('cart_discount');
        session()->forget('applied_coupon');
        
        return back()->with('success', 'Carrito vaciado');
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string'
        ]);

        $coupon = Coupon::where('code', $request->coupon_code)
            ->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        if (!$coupon) {
            return back()->with('error', 'Cupón inválido o expirado');
        }

        if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
            return back()->with('error', 'Este cupón ya alcanzó su límite de uso');
        }

        $cart = $this->getCart();
        $subtotal = $this->calculateSubtotal($cart);

        if ($coupon->min_amount && $subtotal < $coupon->min_amount) {
            return back()->with('error', 'El monto mínimo para este cupón es $' . $coupon->min_amount);
        }

        // Calcular descuento
        $discount = 0;
        if ($coupon->discount_type === 'percentage') {
            $discount = ($subtotal * $coupon->discount_value) / 100;
            if ($coupon->max_discount && $discount > $coupon->max_discount) {
                $discount = $coupon->max_discount;
            }
        } else {
            $discount = $coupon->discount_value;
        }

        session()->put('cart_discount', $discount);
        session()->put('applied_coupon', $coupon->code);

        return back()->with('success', '¡Cupón aplicado! Descuento de $' . number_format($discount, 2));
    }

    public function removeCoupon()
    {
        session()->forget('cart_discount');
        session()->forget('applied_coupon');
        
        return back()->with('success', 'Cupón removido');
    }

    public function checkout()
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('info', 'Por favor inicia sesión para continuar con la compra');
        }

        $cart = $this->getCart();
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío');
        }

        $subtotal = $this->calculateSubtotal($cart);
        $discount = session()->get('cart_discount', 0);
        $total = $subtotal - $discount;

        return view('shop.checkout.index', compact('cart', 'subtotal', 'discount', 'total'));
    }

    // Métodos auxiliares
    private function getCart()
    {
        return session()->get('cart', []);
    }

    private function calculateSubtotal($cart)
    {
        return array_sum(array_map(function($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));
    }
}
