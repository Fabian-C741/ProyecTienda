<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AuditLog
{
    /**
     * Acciones que deben ser auditadas
     */
    protected $auditableActions = [
        'POST', 'PUT', 'PATCH', 'DELETE'
    ];

    /**
     * Rutas sensibles que siempre se auditan
     */
    protected $sensitiveRoutes = [
        '/login',
        '/register',
        '/orders',
        '/payments',
        '/users',
        '/tenants',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $shouldAudit = $this->shouldAudit($request);

        if ($shouldAudit) {
            $this->logRequest($request);
        }

        $response = $next($request);

        if ($shouldAudit) {
            $this->logResponse($request, $response);
        }

        return $response;
    }

    /**
     * Determinar si la solicitud debe ser auditada
     */
    protected function shouldAudit(Request $request): bool
    {
        // Auditar todos los métodos que modifican datos
        if (in_array($request->method(), $this->auditableActions)) {
            return true;
        }

        // Auditar rutas sensibles incluso con GET
        foreach ($this->sensitiveRoutes as $route) {
            if (str_contains($request->path(), $route)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Registrar información de la solicitud
     */
    protected function logRequest(Request $request): void
    {
        $user = $request->user();
        
        $logData = [
            'timestamp' => now()->toIso8601String(),
            'method' => $request->method(),
            'path' => $request->path(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => $user?->id,
            'user_email' => $user?->email,
            'tenant_id' => $user?->tenant_id ?? $request->input('tenant_id'),
        ];

        // No registrar contraseñas ni tokens
        $sanitizedInput = collect($request->except([
            'password', 
            'password_confirmation', 
            'token',
            'access_token',
            'api_key'
        ]))->toArray();

        if (!empty($sanitizedInput)) {
            $logData['input'] = $sanitizedInput;
        }

        Log::channel('audit')->info('API Request', $logData);
    }

    /**
     * Registrar información de la respuesta
     */
    protected function logResponse(Request $request, Response $response): void
    {
        $user = $request->user();

        $logData = [
            'timestamp' => now()->toIso8601String(),
            'method' => $request->method(),
            'path' => $request->path(),
            'status_code' => $response->getStatusCode(),
            'user_id' => $user?->id,
            'tenant_id' => $user?->tenant_id,
        ];

        // Registrar errores con más detalle
        if ($response->getStatusCode() >= 400) {
            $content = $response->getContent();
            $logData['error'] = json_decode($content, true) ?? $content;
            
            Log::channel('audit')->warning('API Error Response', $logData);
        } else {
            Log::channel('audit')->info('API Response', $logData);
        }
    }
}
