<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Obtener estadísticas del dashboard
     */
    public function stats(Request $request)
    {
        $tenantId = $request->user()->tenant_id;
        $isSuperAdmin = $request->user()->hasRole('super_admin');

        // Construir query base para órdenes
        $ordersQuery = Order::query();
        if (!$isSuperAdmin) {
            $ordersQuery->where('tenant_id', $tenantId);
        }

        // Estadísticas generales
        $stats = [
            'totals' => [
                'orders' => (clone $ordersQuery)->count(),
                'orders_pending' => (clone $ordersQuery)->where('status', 'pending')->count(),
                'orders_completed' => (clone $ordersQuery)->where('status', 'completed')->count(),
                'revenue_total' => (clone $ordersQuery)->where('status', 'completed')->sum('total'),
                'revenue_pending' => (clone $ordersQuery)->where('status', 'pending')->sum('total'),
                'products' => Product::when(!$isSuperAdmin, fn($q) => $q->where('tenant_id', $tenantId))->count(),
                'products_active' => Product::when(!$isSuperAdmin, fn($q) => $q->where('tenant_id', $tenantId))->where('is_active', true)->count(),
                'customers' => User::when(!$isSuperAdmin, fn($q) => $q->where('tenant_id', $tenantId))->whereHas('roles', fn($q) => $q->where('name', 'cliente'))->count(),
                'reviews' => ProductReview::whereHas('product', fn($q) => !$isSuperAdmin ? $q->where('tenant_id', $tenantId) : $q)->count(),
                'average_rating' => ProductReview::whereHas('product', fn($q) => !$isSuperAdmin ? $q->where('tenant_id', $tenantId) : $q)->avg('rating') ?? 0,
            ],
        ];

        // Ventas por día (últimos 30 días)
        $salesByDay = (clone $ordersQuery)
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subDays(30))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as orders'),
                DB::raw('SUM(total) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        $stats['sales_by_day'] = $salesByDay;

        // Productos más vendidos
        $topProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->when(!$isSuperAdmin, fn($q) => $q->where('products.tenant_id', $tenantId))
            ->where('orders.status', 'completed')
            ->select(
                'products.id',
                'products.name',
                'products.price',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue')
            )
            ->groupBy('products.id', 'products.name', 'products.price')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();

        $stats['top_products'] = $topProducts;

        // Productos con poco stock
        $lowStockProducts = Product::when(!$isSuperAdmin, fn($q) => $q->where('tenant_id', $tenantId))
            ->where('is_active', true)
            ->where('stock', '<=', 10)
            ->orderBy('stock', 'asc')
            ->limit(10)
            ->get(['id', 'name', 'sku', 'stock', 'price']);

        $stats['low_stock_products'] = $lowStockProducts;

        // Órdenes recientes
        $recentOrders = (clone $ordersQuery)
            ->with(['user:id,name,email', 'items'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $stats['recent_orders'] = $recentOrders;

        // Distribución de estados de órdenes
        $ordersByStatus = (clone $ordersQuery)
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        $stats['orders_by_status'] = $ordersByStatus;

        // Métodos de pago más usados
        $paymentMethods = (clone $ordersQuery)
            ->where('status', 'completed')
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total) as revenue'))
            ->groupBy('payment_method')
            ->get();

        $stats['payment_methods'] = $paymentMethods;

        return response()->json($stats);
    }

    /**
     * Obtener gráfica de ingresos
     */
    public function revenueChart(Request $request)
    {
        $period = $request->get('period', 'month'); // day, week, month, year
        $tenantId = $request->user()->tenant_id;
        $isSuperAdmin = $request->user()->hasRole('super_admin');

        $ordersQuery = Order::where('status', 'completed');
        if (!$isSuperAdmin) {
            $ordersQuery->where('tenant_id', $tenantId);
        }

        switch ($period) {
            case 'day':
                $data = (clone $ordersQuery)
                    ->where('created_at', '>=', now()->subDays(7))
                    ->select(
                        DB::raw('DATE(created_at) as period'),
                        DB::raw('SUM(total) as revenue'),
                        DB::raw('COUNT(*) as orders')
                    )
                    ->groupBy('period')
                    ->orderBy('period')
                    ->get();
                break;

            case 'week':
                $data = (clone $ordersQuery)
                    ->where('created_at', '>=', now()->subWeeks(12))
                    ->select(
                        DB::raw('YEARWEEK(created_at) as period'),
                        DB::raw('SUM(total) as revenue'),
                        DB::raw('COUNT(*) as orders')
                    )
                    ->groupBy('period')
                    ->orderBy('period')
                    ->get();
                break;

            case 'year':
                $data = (clone $ordersQuery)
                    ->where('created_at', '>=', now()->subYears(3))
                    ->select(
                        DB::raw('YEAR(created_at) as period'),
                        DB::raw('SUM(total) as revenue'),
                        DB::raw('COUNT(*) as orders')
                    )
                    ->groupBy('period')
                    ->orderBy('period')
                    ->get();
                break;

            default: // month
                $data = (clone $ordersQuery)
                    ->where('created_at', '>=', now()->subMonths(12))
                    ->select(
                        DB::raw('DATE_FORMAT(created_at, "%Y-%m") as period'),
                        DB::raw('SUM(total) as revenue'),
                        DB::raw('COUNT(*) as orders')
                    )
                    ->groupBy('period')
                    ->orderBy('period')
                    ->get();
                break;
        }

        return response()->json([
            'period' => $period,
            'data' => $data,
        ]);
    }
}
