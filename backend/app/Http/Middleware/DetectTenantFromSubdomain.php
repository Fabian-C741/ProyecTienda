<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Tenant;

class DetectTenantFromSubdomain
{
    /**
     * Detectar tenant desde el subdominio
     * 
     * Ejemplo: tienda-demo.ingreso-tienda.kcrsf.com → slug = "tienda-demo"
     */
    public function handle(Request $request, Closure $next)
    {
        $host = $request->getHost();
        
        // Extraer subdominio
        $parts = explode('.', $host);
        
        // Si tiene más de 2 partes, el primero es el subdominio
        // tienda-demo.ingreso-tienda.kcrsf.com → ["tienda-demo", "ingreso-tienda", "kcrsf", "com"]
        if (count($parts) > 2) {
            $subdomain = $parts[0];
            
            // Ignorar subdominios de sistema
            $systemSubdomains = ['www', 'api', 'admin', 'panel', 'cdn', 'static'];
            if (in_array($subdomain, $systemSubdomains)) {
                return $next($request);
            }
            
            // Buscar tenant por slug (el subdominio es el slug)
            $tenant = Tenant::where('slug', $subdomain)
                ->where('status', 'active')
                ->first();
            
            if ($tenant) {
                // Compartir tenant globalmente
                app()->instance('tenant', $tenant);
                view()->share('tenant', $tenant);
                $request->attributes->set('tenant', $tenant);
                
                // Agregar tenant_id para middleware ValidateTenant si es necesario
                $request->headers->set('X-Tenant-ID', $tenant->id);
            } else {
                // Subdominio no encontrado
                abort(404, 'Tienda no encontrada');
            }
        }
        
        return $next($request);
    }
}
