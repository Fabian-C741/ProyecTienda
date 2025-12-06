<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Middleware de CORS (Cross-Origin Resource Sharing)
 * 
 * Controla qué dominios pueden acceder a tu API
 * IMPORTANTE: Solo permite dominios específicos en producción
 */
class Cors
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
        // Lista blanca de orígenes permitidos
        $allowedOrigins = $this->getAllowedOrigins();

        $origin = $request->header('Origin');

        // Verificar si el origen está permitido
        if (in_array($origin, $allowedOrigins)) {
            $response = $next($request);

            // Configurar headers de CORS de forma segura
            $response->headers->set('Access-Control-Allow-Origin', $origin);
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, X-Tenant-ID, Accept');
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
            $response->headers->set('Access-Control-Max-Age', '86400'); // 24 horas

            return $response;
        }

        // Si es una petición OPTIONS (preflight) pero no está en la lista
        if ($request->getMethod() === 'OPTIONS') {
            return response('', 204);
        }

        // Para otras peticiones, continuar sin headers CORS
        return $next($request);
    }

    /**
     * Obtener lista de orígenes permitidos
     * 
     * @return array
     */
    private function getAllowedOrigins(): array
    {
        $origins = [];

        // Frontend principal
        if ($frontendUrl = config('app.frontend_url')) {
            $origins[] = $frontendUrl;
        }

        // Orígenes adicionales desde variable de entorno
        if ($allowedOrigins = config('cors.allowed_origins')) {
            $additionalOrigins = explode(',', $allowedOrigins);
            $origins = array_merge($origins, $additionalOrigins);
        }

        // En desarrollo local, permitir localhost
        if (config('app.env') === 'local') {
            $origins = array_merge($origins, [
                'http://localhost:5173',
                'http://localhost:3000',
                'http://localhost:8080',
                'http://127.0.0.1:5173',
                'http://127.0.0.1:3000',
            ]);
        }

        return array_filter($origins);
    }
}
