<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Notifications\OrderStatusChanged;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Listar todas las órdenes (admin)
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items.product', 'tenant']);

        // Filtro por tenant
        if (!$request->user()->hasRole('super_admin')) {
            $query->where('tenant_id', $request->user()->tenant_id);
        }

        // Filtros
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->has('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($query) use ($request) {
                      $query->where('name', 'like', '%' . $request->search . '%')
                            ->orWhere('email', 'like', '%' . $request->search . '%');
                  });
            });
        }

        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->orderBy('created_at', 'desc')
                       ->paginate($request->per_page ?? 20);

        return response()->json($orders);
    }

    /**
     * Mostrar una orden específica
     */
    public function show(Request $request, $id)
    {
        $order = Order::with(['user', 'items.product', 'tenant'])->findOrFail($id);

        // Verificar autorización
        if (!$request->user()->hasRole('super_admin') && $order->tenant_id != $request->user()->tenant_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        return response()->json($order);
    }

    /**
     * Actualizar el estado de una orden
     */
    public function updateStatus(Request $request, $id)
    {
        $order = Order::with('user')->findOrFail($id);

        // Verificar autorización
        if (!$request->user()->hasRole('super_admin') && $order->tenant_id != $request->user()->tenant_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,processing,shipped,delivered,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $oldStatus = $order->status;
        $newStatus = $request->status;

        // Validaciones de cambio de estado
        if ($oldStatus === 'cancelled') {
            return response()->json([
                'message' => 'No se puede cambiar el estado de una orden cancelada'
            ], 422);
        }

        if ($oldStatus === 'completed' && $newStatus !== 'completed') {
            return response()->json([
                'message' => 'No se puede cambiar el estado de una orden completada'
            ], 422);
        }

        $order->update([
            'status' => $newStatus,
            'notes' => $request->notes ?? $order->notes,
        ]);

        // Notificar al usuario del cambio de estado
        try {
            $order->user->notify(new OrderStatusChanged($order, $oldStatus, $newStatus));
        } catch (\Exception $e) {
            // Log pero no fallar si el email falla
            \Log::warning('Failed to send order status notification', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'message' => 'Estado de orden actualizado exitosamente',
            'order' => $order->fresh(['user', 'items.product']),
        ]);
    }
}
