<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

/**
 * TenantMiddleware
 * 
 * Middleware para proteger rutas de tenant_admin y establecer
 * el contexto del tenant actual para scope automático
 */
class TenantMiddleware
{
    /**
     * Handle an incoming request.
     * Ensures only tenant_admin users can access tenant routes
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('tenant.login')->with('error', 'Debes iniciar sesión para acceder.');
        }
        
        $user = auth()->user();
        
        // Check if user has tenant_admin role
        if ($user->role !== 'tenant_admin') {
            abort(403, 'No tienes permisos para acceder al panel de vendedor.');
        }
        
        // Check if user has a tenant assigned
        if (!$user->tenant_id) {
            abort(403, 'No tienes una tienda asignada. Contacta al administrador.');
        }

        // Verificar que el tenant esté activo
        $tenant = $user->tenant;
        if (!$tenant || !$tenant->is_active) {
            abort(403, 'Tu tienda está inactiva. Contacta al administrador.');
        }

        // Compartir tenant con todas las vistas
        View::share('currentTenant', $tenant);
        
        // Guardar tenant en la request para uso en controladores
        $request->merge(['current_tenant_id' => $tenant->id]);
        
        return $next($request);
    }
}

