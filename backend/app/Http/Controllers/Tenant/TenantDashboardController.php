<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TenantDashboardController extends Controller
{
    public function index()
    {
        $tenant = auth()->user()->tenant;
        
        if (!$tenant) {
            return redirect()->route('login')->with('error', 'No tienes una tienda asociada');
        }

        // Estadísticas para la vista
        $stats = [
            'total_sales' => Order::where('tenant_id', $tenant->id)
                ->where('status', 'completed')
                ->sum('total_amount'),
            'total_orders' => Order::where('tenant_id', $tenant->id)->count(),
            'total_products' => Product::where('tenant_id', $tenant->id)->count(),
            'total_customers' => Order::where('tenant_id', $tenant->id)
                ->distinct('user_id')
                ->count('user_id'),
        ];

        // Últimas órdenes
        $latestOrders = Order::where('tenant_id', $tenant->id)
            ->with('user')
            ->latest()
            ->limit(5)
            ->get();

        // Productos más vendidos
        $topProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.tenant_id', $tenant->id)
            ->select('products.name', DB::raw('SUM(order_items.quantity) as total'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Productos con bajo stock
        $lowStock = Product::where('tenant_id', $tenant->id)
            ->where('stock_quantity', '<=', 5)
            ->where('is_active', true)
            ->get();

        return view('tenant.dashboard', compact('stats', 'latestOrders', 'topProducts', 'lowStock'));
    }
}
