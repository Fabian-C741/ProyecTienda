<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardWebController extends Controller
{
    public function index()
    {
        try {
            $stats = [
                'total_products' => Product::count(),
                'active_products' => Product::where('is_active', true)->count(),
                'total_orders' => Order::count(),
                'pending_orders' => Order::where('status', 'pending')->count(),
                'total_revenue' => Order::whereIn('status', ['delivered', 'shipped'])->sum('total'),
                'total_users' => User::count(),
                'new_users' => User::whereDate('created_at', '>=', now()->subDays(30))->count(),
            ];

            // Ventas de los últimos 7 días
            $salesChart = Order::where('created_at', '>=', now()->subDays(7))
                ->whereIn('status', ['delivered', 'shipped'])
                ->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('SUM(total) as total'),
                    DB::raw('COUNT(*) as count')
                )
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            // Productos más vendidos
            $topProductsChart = Product::select('products.name', DB::raw('COUNT(order_items.id) as sales'))
                ->join('order_items', 'products.id', '=', 'order_items.product_id')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->whereIn('orders.status', ['delivered', 'shipped'])
                ->groupBy('products.id', 'products.name')
                ->orderBy('sales', 'desc')
                ->limit(5)
                ->get();

            // Distribución de órdenes por estado
            $ordersStatusChart = Order::select('status', DB::raw('COUNT(*) as count'))
                ->groupBy('status')
                ->get();

            $recent_orders = Order::latest()
                ->take(5)
                ->get();

            $top_products = Product::select('products.*', DB::raw('COUNT(order_items.id) as sales_count'))
                ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
                ->groupBy('products.id')
                ->orderBy('sales_count', 'desc')
                ->take(5)
                ->get();

            return view('admin.dashboard', compact(
                'stats', 
                'recent_orders', 
                'top_products',
                'salesChart',
                'topProductsChart',
                'ordersStatusChart'
            ));
        } catch (\Exception $e) {
            return view('admin.dashboard', [
                'stats' => [
                    'total_products' => 0,
                    'active_products' => 0,
                    'total_orders' => 0,
                    'pending_orders' => 0,
                    'total_revenue' => 0,
                    'total_users' => 0,
                    'new_users' => 0,
                ],
                'recent_orders' => [],
                'top_products' => [],
                'salesChart' => [],
                'topProductsChart' => [],
                'ordersStatusChart' => []
            ]);
        }
    }
}
