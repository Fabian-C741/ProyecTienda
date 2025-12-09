<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * DashboardController Unificado
 * 
 * Un solo dashboard que muestra diferente información según el rol:
 * - super_admin: Ve TODO el sistema (estadísticas globales, todos los tenants)
 * - tenant_admin: Ve SOLO su tienda (sus productos, pedidos, estadísticas)
 * - customer: Ve su perfil y pedidos
 */
class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        // Redirigir según rol
        switch ($user->role) {
            case 'super_admin':
                return $this->superAdminDashboard();
            
            case 'tenant_admin':
                return $this->tenantAdminDashboard();
            
            case 'customer':
                return $this->customerDashboard();
            
            default:
                abort(403, 'Rol no reconocido');
        }
    }

    /**
     * Dashboard del Super Admin
     * Ve TODO: estadísticas globales, todos los tenants, comisiones totales
     */
    private function superAdminDashboard()
    {
        $stats = [
            'total_tenants' => Tenant::count(),
            'active_tenants' => Tenant::where('status', 'active')->count(),
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total_amount'),
            'total_users' => User::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
        ];

        // Comisiones totales
        $tenants = Tenant::all();
        $totalCommissions = 0;
        
        foreach ($tenants as $tenant) {
            $tenantSales = Order::where('tenant_id', $tenant->id)
                ->where('payment_status', 'paid')
                ->sum('total_amount');
            $totalCommissions += $tenantSales * ($tenant->commission_rate / 100);
        }
        
        $stats['total_commissions'] = $totalCommissions;

        // Últimos tenants
        $recentTenants = Tenant::latest()->take(5)->get();

        // Últimos pedidos (de todos los tenants)
        $recentOrders = Order::with(['user', 'tenant'])
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.super-admin', compact('stats', 'recentTenants', 'recentOrders'));
    }

    /**
     * Dashboard del Tenant Admin
     * Ve SOLO su tienda: productos, pedidos y estadísticas PROPIAS
     */
    private function tenantAdminDashboard()
    {
        $user = Auth::user();
        
        if (!$user->tenant_id) {
            abort(403, 'No tienes una tienda asignada');
        }

        $tenant = Tenant::find($user->tenant_id);

        if (!$tenant) {
            abort(404, 'Tienda no encontrada');
        }

        if ($tenant->status !== 'active') {
            abort(403, 'Tu tienda está inactiva. Contacta al administrador.');
        }

        // Estadísticas SOLO de SU tienda
        $stats = [
            'my_products' => Product::where('tenant_id', $tenant->id)->count(),
            'active_products' => Product::where('tenant_id', $tenant->id)->where('is_active', true)->count(),
            'my_orders' => Order::where('tenant_id', $tenant->id)->count(),
            'pending_orders' => Order::where('tenant_id', $tenant->id)->where('status', 'pending')->count(),
            'my_revenue' => Order::where('tenant_id', $tenant->id)->where('payment_status', 'paid')->sum('total_amount'),
            'my_customers' => Order::where('tenant_id', $tenant->id)->distinct('user_id')->count('user_id'),
        ];

        // Calcular comisión de SU tienda
        $myCommission = $stats['my_revenue'] * ($tenant->commission_rate / 100);
        $stats['my_commission'] = $myCommission;
        $stats['my_earnings'] = $stats['my_revenue'] - $myCommission;

        // Últimos pedidos de SU tienda
        $recentOrders = Order::where('tenant_id', $tenant->id)
            ->with('user')
            ->latest()
            ->take(10)
            ->get();

        // Productos más vendidos de SU tienda
        $topProducts = Product::where('tenant_id', $tenant->id)
            ->withCount('orderItems')
            ->orderBy('order_items_count', 'desc')
            ->take(5)
            ->get();

        return view('dashboard.tenant-admin', compact('tenant', 'stats', 'recentOrders', 'topProducts'));
    }

    /**
     * Dashboard del Cliente
     * Ve su perfil y sus pedidos
     */
    private function customerDashboard()
    {
        $user = Auth::user();

        // Pedidos del cliente
        $myOrders = Order::where('user_id', $user->id)
            ->with('tenant')
            ->latest()
            ->take(10)
            ->get();

        $stats = [
            'total_orders' => Order::where('user_id', $user->id)->count(),
            'total_spent' => Order::where('user_id', $user->id)->where('payment_status', 'paid')->sum('total_amount'),
            'pending_orders' => Order::where('user_id', $user->id)->where('status', 'pending')->count(),
        ];

        return view('dashboard.customer', compact('stats', 'myOrders'));
    }
}
