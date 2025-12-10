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
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total'),
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
                DB::raw('SUM(total) as revenue'),
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
        ], [
            // Mensajes personalizados para el slug
            'slug.required' => 'El identificador de la tienda es obligatorio',
            'slug.unique' => 'Este identificador ya está en uso. Por favor elige otro nombre único para tu tienda',
            'slug.alpha_dash' => 'El identificador solo puede contener letras, números y guiones',
            'slug.max' => 'El identificador no puede tener más de 255 caracteres',
            // Mensajes para otros campos
            'admin_email.unique' => 'Este correo ya está registrado en el sistema',
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
            'total_revenue' => $tenant->orders()->where('payment_status', 'paid')->sum('total'),
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
        $tenants = Tenant::all()
            ->map(function($tenant) {
                // Obtener pedidos pagados del tenant
                $paidOrders = Order::where('tenant_id', $tenant->id)
                    ->where('payment_status', 'paid')
                    ->get();
                
                $totalSales = $paidOrders->sum('total');
                $commission = $totalSales * ($tenant->commission_rate / 100);

                return [
                    'tenant' => $tenant,
                    'total_sales' => $totalSales,
                    'commission_rate' => $tenant->commission_rate,
                    'commission_amount' => $commission,
                    'tenant_earnings' => $totalSales - $commission,
                    'orders_count' => $paidOrders->count(),
                ];
            });

        $totalCommissions = $tenants->sum('commission_amount');
        $totalSales = $tenants->sum('total_sales');

        return view('super-admin.commissions', compact('tenants', 'totalCommissions', 'totalSales'));
    }

    /**
     * Obtener detalles de un usuario (AJAX)
     */
    public function getUserDetails($id)
    {
        $user = User::with('tenant')->findOrFail($id);
        
        $roleDisplay = match($user->role) {
            'super_admin' => 'Super Administrador',
            'tenant_admin' => 'Administrador de Tienda',
            'customer' => 'Cliente',
            default => $user->role
        };

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'role_display' => $roleDisplay,
            'tenant_name' => $user->tenant ? $user->tenant->name : null,
            'status' => $user->status ?? 'active',
            'created_at' => $user->created_at->format('d/m/Y H:i'),
        ]);
    }

    /**
     * Mostrar formulario de edición de usuario
     */
    public function editUser($id)
    {
        $user = User::findOrFail($id);
        $tenants = Tenant::where('status', 'active')->get();
        
        return view('super-admin.users.edit', compact('user', 'tenants'));
    }

    /**
     * Actualizar usuario
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:super_admin,tenant_admin,customer',
            'status' => 'required|in:active,inactive',
        ];

        // Si cambia la contraseña
        if ($request->filled('password')) {
            $rules['password'] = 'required|min:8|confirmed';
        }

        // Si es tenant_admin, requiere tenant_id
        if ($request->role === 'tenant_admin') {
            $rules['tenant_id'] = 'required|exists:tenants,id';
        }

        $validated = $request->validate($rules);

        // Actualizar datos básicos
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->status = $validated['status'];

        // Actualizar tenant_id según el rol
        if ($validated['role'] === 'tenant_admin') {
            $user->tenant_id = $validated['tenant_id'];
        } else {
            $user->tenant_id = null;
        }

        // Actualizar contraseña si se proporcionó
        if ($request->filled('password')) {
            $user->password = bcrypt($validated['password']);
        }

        $user->save();

        return redirect()->route('super-admin.users')
            ->with('success', 'Usuario actualizado exitosamente');
    }

    /**
     * Restablecer contraseña de un usuario (AJAX)
     */
    public function resetUserPassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|min:8',
        ]);

        $user = User::findOrFail($id);
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Contraseña restablecida exitosamente'
        ]);
    }

    /**
     * Eliminar usuario
     */
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        // No permitir eliminar super admins
        if ($user->role === 'super_admin') {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar un Super Administrador'
            ], 403);
        }

        // No permitir eliminar el usuario actual
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes eliminar tu propio usuario'
            ], 403);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Usuario eliminado exitosamente'
        ]);
    }

    /**
     * Ver solicitudes de vendedores
     */
    public function vendorRequests(Request $request)
    {
        $query = \App\Models\VendorRequest::query();

        // Filtro por estado
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $requests = $query->latest()->paginate(20);

        return view('super-admin.vendor-requests.index', compact('requests'));
    }

    /**
     * Ver detalles de una solicitud
     */
    public function showVendorRequest($id)
    {
        $vendorRequest = \App\Models\VendorRequest::findOrFail($id);
        return view('super-admin.vendor-requests.show', compact('vendorRequest'));
    }

    /**
     * Aprobar solicitud y crear tienda
     */
    public function approveVendorRequest(Request $request, $id)
    {
        $vendorRequest = \App\Models\VendorRequest::findOrFail($id);

        if ($vendorRequest->status !== 'pending') {
            return back()->with('error', 'Esta solicitud ya fue procesada');
        }

        $request->validate([
            'commission_rate' => 'required|numeric|min:0|max:100',
        ]);

        DB::beginTransaction();
        try {
            // Crear el tenant
            $tenant = Tenant::create([
                'name' => $vendorRequest->store_name,
                'slug' => $vendorRequest->slug,
                'email' => $vendorRequest->email,
                'phone' => $vendorRequest->phone,
                'description' => $vendorRequest->description,
                'commission_rate' => $request->commission_rate,
                'status' => 'active',
            ]);

            // Generar contraseña temporal
            $temporaryPassword = Str::random(12);

            // Crear usuario admin del tenant
            $adminUser = User::create([
                'name' => $vendorRequest->owner_name,
                'email' => $vendorRequest->email,
                'password' => Hash::make($temporaryPassword),
                'role' => 'tenant_admin',
                'tenant_id' => $tenant->id,
                'status' => 'active',
            ]);

            // Actualizar solicitud
            $vendorRequest->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'admin_notes' => $request->admin_notes,
            ]);

            DB::commit();

            // TODO: Enviar email con credenciales
            // Mail::to($vendorRequest->email)->send(new TenantCreated($tenant, $temporaryPassword));

            return redirect()->route('super-admin.vendor-requests')
                ->with('success', "Tienda '{$tenant->name}' creada exitosamente. Contraseña temporal: {$temporaryPassword}");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al crear la tienda: ' . $e->getMessage());
        }
    }

    /**
     * Rechazar solicitud
     */
    public function rejectVendorRequest(Request $request, $id)
    {
        $vendorRequest = \App\Models\VendorRequest::findOrFail($id);

        if ($vendorRequest->status !== 'pending') {
            return back()->with('error', 'Esta solicitud ya fue procesada');
        }

        $request->validate([
            'admin_notes' => 'required|string|min:10',
        ], [
            'admin_notes.required' => 'Debes especificar el motivo del rechazo',
            'admin_notes.min' => 'El motivo debe tener al menos 10 caracteres',
        ]);

        $vendorRequest->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'admin_notes' => $request->admin_notes,
        ]);

        // TODO: Enviar email notificando rechazo
        // Mail::to($vendorRequest->email)->send(new RequestRejected($vendorRequest));

        return redirect()->route('super-admin.vendor-requests')
            ->with('success', 'Solicitud rechazada');
    }
}
