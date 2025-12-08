<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $tenant = auth()->user()->tenant;
        
        $query = Order::where('tenant_id', $tenant->id)->with('user');

        // Filtros
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_name', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_email', 'like', '%' . $request->search . '%');
            });
        }

        $orders = $query->latest()->paginate(20);

        return view('tenant.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);

        $order->load('items.product', 'user');

        return view('tenant.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);

        $order->update(['status' => $request->status]);

        // Actualizar fechas según el estado
        if ($request->status === 'shipped' && !$order->shipped_at) {
            $order->update(['shipped_at' => now()]);
        }

        if ($request->status === 'delivered' && !$order->delivered_at) {
            $order->update([
                'delivered_at' => now(),
                'payment_status' => 'paid'
            ]);
        }

        return back()->with('success', 'Estado del pedido actualizado correctamente');
    }
}
