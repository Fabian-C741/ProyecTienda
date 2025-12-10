<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Tenant;

class DetectTenantBySubdomain
{
    /**
     * Detecta el tenant por el subdominio y lo establece en la sesión/request
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        
        // Extraer el subdominio
        // Ejemplo: techstore.ingreso-tienda.kcrsf.com → techstore
        $parts = explode('.', $host);
        
        // Si es el dominio principal (sin subdominio) o localhost
        if (count($parts) <= 2 || in_array($parts[0], ['www', 'ingreso-tienda', 'localhost'])) {
            // No hay tenant específico, es el sitio principal
            session()->forget('tenant_id');
            session()->forget('tenant');
            return $next($request);
        }
        
        // El primer elemento es el subdominio (slug de la tienda)
        $subdomain = $parts[0];
        
        // Buscar el tenant por slug
        $tenant = Tenant::where('slug', $subdomain)
            ->where('status', 'active')
            ->first();
        
        if ($tenant) {
            // Guardar tenant en sesión y request
            session(['tenant_id' => $tenant->id]);
            session(['tenant' => $tenant]);
            $request->merge(['tenant_id' => $tenant->id]);
            $request->attributes->set('tenant', $tenant);
        } else {
            // Subdominio no encontrado o tienda inactiva
            session()->forget('tenant_id');
            session()->forget('tenant');
            
            // Mostrar página de error bonita
            return response()->view('errors.tenant-not-found', [
                'subdomain' => $subdomain
            ], 404);
        }
        
        return $next($request);
    }
}
