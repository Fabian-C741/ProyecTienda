<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * SuperAdminMiddleware
 * 
 * Protege las rutas del panel de Super Admin.
 * Solo usuarios con rol 'super_admin' pueden acceder.
 */
class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar autenticación
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión para acceder.');
        }
        
        $user = auth()->user();
        
        // Verificar rol de super admin
        if ($user->role !== 'super_admin') {
            abort(403, 'Acceso denegado. Solo Super Administradores pueden acceder a esta sección.');
        }
        
        return $next($request);
    }
}
