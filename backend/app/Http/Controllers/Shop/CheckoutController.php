<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\TenantPaymentGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío');
        }

        $subtotal = $this->calculateSubtotal($cart);
        $discount = session()->get('cart_discount', 0);
        $shipping = 0; // Puedes calcular shipping aquí
        $total = $subtotal - $discount + $shipping;

        $user = auth()->user();

        return view('shop.checkout.index', compact('cart', 'subtotal', 'discount', 'shipping', 'total', 'user'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:mercadopago,bank_transfer,cash',
            'shipping_address' => 'required|string|max:500',
            'shipping_city' => 'required|string|max:100',
            'shipping_phone' => 'required|string|max:20',
            'notes' => 'nullable|string|max:500',
        ]);

        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío');
        }

        DB::beginTransaction();

        try {
            $subtotal = $this->calculateSubtotal($cart);
            $discount = session()->get('cart_discount', 0);
            $shipping = 0;
            $total = $subtotal - $discount + $shipping;

            // Obtener tenant_id del primer producto (asumimos todos son del mismo tenant)
            $firstProduct = array_values($cart)[0];
            $tenantId = $firstProduct['tenant_id'] ?? null;

            // Crear la orden
            $order = Order::create([
                'tenant_id' => $tenantId,
                'user_id' => auth()->id(),
                'order_number' => $this->generateOrderNumber(),
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => $request->payment_method,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'shipping' => $shipping,
                'total' => $total,
                'customer_name' => auth()->user()->name,
                'customer_email' => auth()->user()->email,
                'customer_phone' => $request->shipping_phone,
                'shipping_address' => $request->shipping_address,
                'shipping_city' => $request->shipping_city,
                'notes' => $request->notes,
            ]);

            // Crear items de la orden y actualizar stock
            foreach ($cart as $productId => $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'product_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);

                // Actualizar stock
                $product = Product::find($productId);
                if ($product && $product->track_inventory) {
                    $product->decrement('stock', $item['quantity']);
                }
            }

            // Actualizar uso de cupón si se aplicó
            if (session()->has('applied_coupon')) {
                $coupon = Coupon::where('code', session('applied_coupon'))->first();
                if ($coupon) {
                    $coupon->increment('used_count');
                }
            }

            DB::commit();

            // Limpiar carrito y sesión
            session()->forget(['cart', 'cart_discount', 'applied_coupon']);

            // Si es Mercado Pago, redirigir al pago
            if ($request->payment_method === 'mercadopago') {
                return $this->initiateMercadoPagoPayment($order);
            }

            return redirect()->route('order.success', $order->id)->with('success', '¡Pedido realizado con éxito!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al procesar el pedido: ' . $e->getMessage());
        }
    }

    public function success($orderId)
    {
        $order = Order::where('id', $orderId)
            ->where('user_id', auth()->id())
            ->with('items')
            ->firstOrFail();

        return view('shop.checkout.success', compact('order'));
    }

    private function initiateMercadoPagoPayment($order)
    {
        // Obtener configuración de Mercado Pago del tenant
        $paymentGateway = TenantPaymentGateway::where('tenant_id', $order->tenant_id)
            ->where('gateway_name', 'mercadopago')
            ->where('is_active', true)
            ->first();

        if (!$paymentGateway) {
            return redirect()->route('order.success', $order->id)
                ->with('warning', 'Mercado Pago no está configurado. Contacta al vendedor.');
        }

        // Aquí integrarías con la API de Mercado Pago
        // Por ahora, solo redirigimos a la página de éxito
        return redirect()->route('order.success', $order->id)
            ->with('info', 'Serás redirigido a Mercado Pago para completar el pago.');
    }

    private function generateOrderNumber()
    {
        return 'ORD-' . strtoupper(Str::random(10));
    }

    private function calculateSubtotal($cart)
    {
        return array_sum(array_map(function($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));
    }
}
