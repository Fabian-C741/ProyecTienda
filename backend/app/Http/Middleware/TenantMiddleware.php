<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $tenantId = $request->header('X-Tenant-ID') ?? $request->get('tenant_id');
        
        if ($tenantId) {
            // Guardar el tenant_id en el request para uso posterior
            $request->merge(['current_tenant_id' => $tenantId]);
        }

        return $next($request);
    }
}
