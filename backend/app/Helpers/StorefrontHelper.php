<?php

if (!function_exists('storefront_route')) {
    /**
     * Genera URL para rutas de storefront (compatible con subdomain y slug)
     */
    function storefront_route($name, $parameters = [])
    {
        // Asegurar que $parameters sea un array
        if (!is_array($parameters)) {
            $parameters = [$parameters];
        }
        
        // Intentar obtener tenant desde diferentes fuentes
        $tenant = null;
        
        // 1. Desde view (compartido por el controller)
        $shared = view()->getShared();
        if (isset($shared['tenant'])) {
            $tenant = $shared['tenant'];
        }
        
        // 2. Desde app container (middleware subdomain)
        if (!$tenant && app()->bound('tenant')) {
            $tenant = app('tenant');
        }
        
        if (!$tenant) {
            throw new \Exception('Tenant not found for storefront route generation');
        }

        // Si estamos en un subdominio, usar rutas de subdomain
        $host = request()->getHost();
        $mainDomain = config('app.main_domain', 'ingreso-tienda.kcrsf.com');
        
        // Verificar si es un subdominio (no es el dominio principal ni www)
        $isSubdomain = $host !== $mainDomain && 
                      $host !== 'www.' . $mainDomain && 
                      str_ends_with($host, '.' . $mainDomain);
        
        if ($isSubdomain) {
            // Usar rutas de subdomain
            return route('storefront.' . $name, $parameters);
        } else {
            // Usar rutas alternativas con slug
            return route('storefront.alt.' . $name, array_merge(['slug' => $tenant->slug], $parameters));
        }
    }
}
