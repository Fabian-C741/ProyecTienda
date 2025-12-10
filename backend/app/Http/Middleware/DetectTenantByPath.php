<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Tenant;

/**
 * Detecta el tenant por la ruta URL en vez de subdominio
 * 
 * Ejemplos:
 * - ingreso-tienda.kcrsf.com/tienda/otech → tenant con slug 'otech'
 * - ingreso-tienda.kcrsf.com/tienda/moda-latina → tenant con slug 'moda-latina'
 * - ingreso-tienda.kcrsf.com → sin tenant (sitio principal)
 * 
 * Compatible con hosting compartido (no requiere SSL wildcard)
 */
class DetectTenantByPath
{
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si la URL tiene el patrón /tienda/{slug}
        $path = $request->path();
        
        // Patrón: tienda/nombre-tienda o tienda/nombre-tienda/cualquier-cosa
        if (preg_match('#^tienda/([a-z0-9-]+)#', $path, $matches)) {
            $slug = $matches[1];
            
            // Buscar el tenant por slug
            $tenant = Tenant::where('slug', $slug)
                ->where('status', 'active')
                ->first();
            
            if ($tenant) {
                // Guardar tenant en sesión y request
                session(['tenant_id' => $tenant->id]);
                session(['tenant' => $tenant]);
                $request->merge(['tenant_id' => $tenant->id]);
                $request->attributes->set('tenant', $tenant);
                
                return $next($request);
            } else {
                // Tienda no encontrada o inactiva
                session()->forget('tenant_id');
                session()->forget('tenant');
                
                return response()->view('errors.tenant-not-found', [
                    'slug' => $slug,
                    'type' => 'path'
                ], 404);
            }
        }
        
        // No es una ruta de tienda, continuar sin tenant
        session()->forget('tenant_id');
        session()->forget('tenant');
        
        return $next($request);
    }
}
