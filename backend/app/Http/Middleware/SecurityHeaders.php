<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Middleware de Headers de Seguridad
 * 
 * Agrega headers de seguridad HTTP para proteger contra ataques comunes:
 * - XSS (Cross-Site Scripting)
 * - Clickjacking
 * - MIME Sniffing
 * - Content Type Injection
 */
class SecurityHeaders
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
        $response = $next($request);

        // Prevenir XSS (Cross-Site Scripting)
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Prevenir Clickjacking
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Prevenir MIME Sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Referrer Policy (no enviar info a sitios externos)
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Content Security Policy (CSP)
        if (config('app.env') === 'production') {
            $csp = $this->getContentSecurityPolicy();
            $response->headers->set('Content-Security-Policy', $csp);
        }

        // Strict Transport Security (HSTS) - Solo HTTPS
        if ($request->secure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        // Permissions Policy (antes Feature-Policy)
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        // Remover header que expone versión de PHP
        $response->headers->remove('X-Powered-By');

        return $response;
    }

    /**
     * Generar política de Content Security Policy
     * 
     * @return string
     */
    private function getContentSecurityPolicy(): string
    {
        $frontendUrl = config('app.frontend_url', 'https://tudominio.com');
        $appUrl = config('app.url', 'https://api.tudominio.com');

        $policies = [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval'", // Necesario para algunas librerías
            "style-src 'self' 'unsafe-inline'",
            "img-src 'self' data: https:",
            "font-src 'self' data:",
            "connect-src 'self' {$frontendUrl} {$appUrl} https://api.mercadopago.com https://api.stripe.com",
            "frame-ancestors 'self'",
            "base-uri 'self'",
            "form-action 'self'",
        ];

        return implode('; ', $policies);
    }
}
