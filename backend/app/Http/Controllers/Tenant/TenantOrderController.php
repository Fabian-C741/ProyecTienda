<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class TenantOrderController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->tenant_id || !$user->tenant) {
            return redirect()->route('dashboard.index')
                ->with('error', 'No tienes una tienda asignada.');
        }
        
        $tenant = $user->tenant;
        
        $query = Order::where('tenant_id', $tenant->id)->with('user');

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(20);

        return view('tenant.orders.index', compact('orders', 'tenant'));
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);

        $order->load(['user', 'items.product']);

        return view('tenant.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);

        $order->update($validated);

        return back()->with('success', 'Estado de orden actualizado');
    }
}
