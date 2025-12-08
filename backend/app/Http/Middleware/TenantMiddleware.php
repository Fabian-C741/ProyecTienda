<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

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
        
        return $next($request);
    }
}

