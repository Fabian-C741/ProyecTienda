<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

/**
 * SuperAdminController
 * 
 * Gestión completa de tenants, usuarios y estadísticas globales
 * Solo accesible para Super Administradores
 */
class SuperAdminController extends Controller
{
    /**
     * Dashboard del Super Admin con estadísticas globales
     */
    public function dashboard()
    {
        $stats = [
            'total_tenants' => Tenant::count(),
            'active_tenants' => Tenant::where('status', 'active')->count(),
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total_amount'),
            'total_users' => User::count(),
        ];

        // Tenants recientes
        $recentTenants = Tenant::latest()->take(5)->get();

        // Órdenes recientes (todas las tiendas)
        $recentOrders = Order::with(['user', 'tenant'])
            ->latest()
            ->take(10)
            ->get();

        // Ingresos por mes (últimos 6 meses)
        $monthlyRevenue = Order::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('SUM(total_amount) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('super-admin.dashboard', compact(
            'stats',
            'recentTenants',
            'recentOrders',
            'monthlyRevenue'
        ));
    }

    /**
     * Lista de todos los tenants
     */
    public function tenants(Request $request)
    {
        $query = Tenant::withCount(['products', 'orders', 'users']);

        // Búsqueda
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filtro por estado
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $tenants = $query->paginate(15);

        return view('super-admin.tenants.index', compact('tenants'));
    }

    /**
     * Mostrar formulario para crear nuevo tenant
     */
    public function createTenant()
    {
        return view('super-admin.tenants.create');
    }

    /**
     * Guardar nuevo tenant
     */
    public function storeTenant(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tenants,slug|alpha_dash',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'description' => 'nullable|string|max:1000',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|unique:users,email',
            'admin_password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            // Crear tenant
            $tenant = Tenant::create([
                'name' => $request->name,
                'slug' => Str::slug($request->slug),
                'email' => $request->email,
                'phone' => $request->phone,
                'description' => $request->description,
                'status' => 'active',
                'commission_rate' => $request->commission_rate ?? 10.00,
            ]);

            // Crear usuario admin del tenant
            $user = User::create([
                'tenant_id' => $tenant->id,
                'name' => $request->admin_name,
                'email' => $request->admin_email,
                'password' => Hash::make($request->admin_password),
                'role' => 'tenant_admin',
                'is_active' => true,
            ]);

            DB::commit();

            return redirect()->route('super-admin.tenants')
                ->with('success', "Tienda '{$tenant->name}' creada exitosamente. Usuario admin: {$user->email}");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al crear la tienda: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Ver detalles de un tenant
     */
    public function showTenant(Tenant $tenant)
    {
        $tenant->load(['users', 'products', 'orders']);

        $stats = [
            'total_products' => $tenant->products()->count(),
            'total_orders' => $tenant->orders()->count(),
            'total_revenue' => $tenant->orders()->where('payment_status', 'paid')->sum('total_amount'),
            'total_users' => $tenant->users()->count(),
        ];

        return view('super-admin.tenants.show', compact('tenant', 'stats'));
    }

    /**
     * Editar tenant
     */
    public function editTenant(Tenant $tenant)
    {
        return view('super-admin.tenants.edit', compact('tenant'));
    }

    /**
     * Actualizar tenant
     */
    public function updateTenant(Request $request, Tenant $tenant)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'description' => 'nullable|string|max:1000',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $tenant->update($request->only([
                'name',
                'email',
                'phone',
                'description',
                'commission_rate',
                'status'
            ]));

            return redirect()->route('super-admin.tenants.show', $tenant)
                ->with('success', 'Tienda actualizada exitosamente.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Eliminar tenant (soft delete)
     */
    public function destroyTenant(Tenant $tenant)
    {
        try {
            // Verificar que no tenga órdenes pendientes
            $pendingOrders = $tenant->orders()->whereIn('payment_status', ['pending', 'processing'])->count();
            
            if ($pendingOrders > 0) {
                return back()->with('error', 'No se puede eliminar. La tienda tiene órdenes pendientes.');
            }

            $tenant->delete();

            return redirect()->route('super-admin.tenants')
                ->with('success', 'Tienda eliminada exitosamente.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }

    /**
     * Cambiar estado del tenant
     */
    public function toggleTenantStatus(Tenant $tenant)
    {
        $newStatus = $tenant->status === 'active' ? 'inactive' : 'active';
        $tenant->update(['status' => $newStatus]);

        $message = $newStatus === 'active' ? 'Tienda activada' : 'Tienda desactivada';

        return back()->with('success', $message);
    }

    /**
     * Gestión de usuarios globales
     */
    public function users(Request $request)
    {
        $query = User::with('tenant');

        // Búsqueda
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filtro por rol
        if ($request->has('role')) {
            $query->where('role', $request->role);
        }

        // Filtro por tenant
        if ($request->has('tenant_id')) {
            $query->where('tenant_id', $request->tenant_id);
        }

        $users = $query->paginate(20);
        $tenants = Tenant::all();

        return view('super-admin.users.index', compact('users', 'tenants'));
    }

    /**
     * Ver estadísticas de comisiones
     */
    public function commissions()
    {
        $tenants = Tenant::with(['orders' => function($q) {
                $q->where('payment_status', 'paid');
            }])
            ->get()
            ->map(function($tenant) {
                $totalSales = $tenant->orders->sum('total_amount');
                $commission = $totalSales * ($tenant->commission_rate / 100);

                return [
                    'tenant' => $tenant,
                    'total_sales' => $totalSales,
                    'commission_rate' => $tenant->commission_rate,
                    'commission_amount' => $commission,
                    'tenant_earnings' => $totalSales - $commission,
                ];
            });

        $totalCommissions = $tenants->sum('commission_amount');

        return view('super-admin.commissions', compact('tenants', 'totalCommissions'));
    }
}
