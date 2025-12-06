<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Tenant;

/**
 * Middleware de Validación de Tenant
 * 
 * Valida que el tenant exista y esté activo
 * Protege contra acceso a recursos de otros tenants
 */
class ValidateTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Obtener tenant_id del header o parámetro
        $tenantId = $request->header('X-Tenant-ID') ?? $request->input('tenant_id');

        // Si no hay tenant_id en rutas públicas, continuar
        if (!$tenantId && !$this->requiresTenant($request)) {
            return $next($request);
        }

        // Validar que tenant_id sea válido
        if ($tenantId && !$this->isValidTenantId($tenantId)) {
            return response()->json([
                'error' => 'Invalid tenant ID format',
                'message' => 'El ID del tenant no es válido'
            ], 400);
        }

        // Buscar tenant
        $tenant = Tenant::find($tenantId);

        // Verificar que existe
        if (!$tenant) {
            return response()->json([
                'error' => 'Tenant not found',
                'message' => 'La tienda no existe'
            ], 404);
        }

        // Verificar que está activo
        if (!$tenant->is_active) {
            return response()->json([
                'error' => 'Tenant inactive',
                'message' => 'Esta tienda está desactivada temporalmente'
            ], 403);
        }

        // Agregar tenant al request para usarlo en controladores
        $request->merge(['tenant' => $tenant]);
        $request->attributes->set('tenant', $tenant);

        return $next($request);
    }

    /**
     * Verificar si la ruta requiere tenant
     * 
     * @param Request $request
     * @return bool
     */
    private function requiresTenant(Request $request): bool
    {
        // Rutas que NO requieren tenant
        $publicRoutes = [
            'api/v1/login',
            'api/v1/register',
            'api/v1/tenants',
        ];

        $path = $request->path();

        foreach ($publicRoutes as $route) {
            if (str_starts_with($path, $route)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Validar formato de tenant_id (UUID v4)
     * 
     * @param string $tenantId
     * @return bool
     */
    private function isValidTenantId(string $tenantId): bool
    {
        // Validar que sea un UUID válido
        $pattern = '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i';
        return preg_match($pattern, $tenantId) === 1;
    }
}
