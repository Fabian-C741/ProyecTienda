<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        $query = Order::with(['items.product', 'user'])
            ->where('user_id', $user->id);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json($orders);
    }

    public function show(Request $request, $orderNumber)
    {
        $user = $request->user();
        
        $order = Order::with(['items.product', 'user', 'tenant'])
            ->where('order_number', $orderNumber)
            ->where('user_id', $user->id)
            ->firstOrFail();

        return response()->json([
            'order' => $order,
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'cart_id' => 'required|exists:carts,id',
            'payment_method' => 'required|string',
            'shipping_address' => 'required|string',
            'shipping_city' => 'required|string',
            'shipping_state' => 'nullable|string',
            'shipping_country' => 'required|string',
            'shipping_postal_code' => 'required|string',
            'customer_name' => 'required|string',
            'customer_email' => 'required|email',
            'customer_phone' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $cart = Cart::with('items.product')->findOrFail($request->cart_id);

        if ($cart->items->isEmpty()) {
            return response()->json([
                'message' => 'El carrito está vacío',
            ], 400);
        }

        // Verificar stock
        foreach ($cart->items as $item) {
            if (!$item->product->isInStock()) {
                return response()->json([
                    'message' => "El producto {$item->product->name} no tiene stock disponible",
                ], 400);
            }

            if ($item->product->track_inventory && $item->product->stock < $item->quantity) {
                return response()->json([
                    'message' => "Stock insuficiente para {$item->product->name}",
                ], 400);
            }
        }

        DB::beginTransaction();

        try {
            $subtotal = $cart->items->sum(function ($item) {
                return $item->price * $item->quantity;
            });

            $tax = $subtotal * 0.0; // Configurar según necesidad
            $shippingCost = 0; // Calcular según lógica de envío
            $total = $subtotal + $tax + $shippingCost;

            $order = Order::create([
                'tenant_id' => $cart->tenant_id,
                'user_id' => $request->user()->id ?? null,
                'order_number' => Order::generateOrderNumber(),
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => $request->payment_method,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping_cost' => $shippingCost,
                'total' => $total,
                'currency' => 'USD',
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'shipping_address' => $request->shipping_address,
                'shipping_city' => $request->shipping_city,
                'shipping_state' => $request->shipping_state,
                'shipping_country' => $request->shipping_country,
                'shipping_postal_code' => $request->shipping_postal_code,
                'notes' => $request->notes,
            ]);

            // Crear items del pedido
            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'product_sku' => $item->product->sku,
                    'product_variant' => $item->variant,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->price * $item->quantity,
                ]);

                // Reducir stock
                if ($item->product->track_inventory) {
                    $item->product->decrement('stock', $item->quantity);
                }
            }

            // Limpiar carrito
            $cart->items()->delete();

            DB::commit();

            return response()->json([
                'message' => 'Pedido creado exitosamente',
                'order' => $order->load('items'),
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'message' => 'Error al crear el pedido',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function cancel(Request $request, $orderNumber)
    {
        $user = $request->user();
        
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', $user->id)
            ->firstOrFail();

        if (!in_array($order->status, ['pending', 'processing'])) {
            return response()->json([
                'message' => 'No se puede cancelar este pedido',
            ], 400);
        }

        DB::beginTransaction();

        try {
            // Restaurar stock
            foreach ($order->items as $item) {
                if ($item->product && $item->product->track_inventory) {
                    $item->product->increment('stock', $item->quantity);
                }
            }

            $order->update([
                'status' => 'cancelled',
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Pedido cancelado exitosamente',
                'order' => $order,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'message' => 'Error al cancelar el pedido',
            ], 500);
        }
    }
}
