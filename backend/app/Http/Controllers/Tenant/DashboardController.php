<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $tenant = auth()->user()->tenant;

        // Estadísticas generales
        $stats = [
            'total_products' => Product::where('tenant_id', $tenant->id)->count(),
            'total_orders' => Order::where('tenant_id', $tenant->id)->count(),
            'pending_orders' => Order::where('tenant_id', $tenant->id)->where('status', 'pending')->count(),
            'total_revenue' => Order::where('tenant_id', $tenant->id)
                ->whereIn('status', ['delivered'])->sum('total'),
            'this_month_revenue' => Order::where('tenant_id', $tenant->id)
                ->whereIn('status', ['delivered'])
                ->whereMonth('created_at', Carbon::now()->month)
                ->sum('total'),
        ];

        // Órdenes recientes
        $recentOrders = Order::where('tenant_id', $tenant->id)
            ->with('user')
            ->latest()
            ->take(10)
            ->get();

        // Productos más vendidos
        $topProducts = Product::where('tenant_id', $tenant->id)
            ->withCount('orderItems')
            ->orderBy('order_items_count', 'desc')
            ->take(5)
            ->get();

        // Productos con bajo stock
        $lowStockProducts = Product::where('tenant_id', $tenant->id)
            ->where('track_inventory', true)
            ->whereColumn('stock', '<=', 'low_stock_threshold')
            ->take(10)
            ->get();

        return view('tenant.dashboard.index', compact('tenant', 'stats', 'recentOrders', 'topProducts', 'lowStockProducts'));
    }
}
