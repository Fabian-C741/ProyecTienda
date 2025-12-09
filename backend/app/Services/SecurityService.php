<?php

namespace App\Services;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

/**
 * SecurityService
 * 
 * Servicio centralizado para manejar la seguridad de datos sensibles:
 * - Sanitización de inputs
 * - Encriptación de datos
 * - Validación de permisos
 * - Prevención de SQL Injection
 * - Protección contra XSS
 */
class SecurityService
{
    /**
     * Sanitizar string para prevenir XSS
     * 
     * @param string|null $input
     * @return string|null
     */
    public static function sanitizeString(?string $input): ?string
    {
        if ($input === null) {
            return null;
        }

        // Eliminar tags HTML peligrosos
        $input = strip_tags($input);
        
        // Escapar caracteres especiales
        $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
        
        // Eliminar caracteres de control
        $input = preg_replace('/[\x00-\x1F\x7F]/', '', $input);
        
        return trim($input);
    }

    /**
     * Sanitizar email
     * 
     * @param string|null $email
     * @return string|null
     */
    public static function sanitizeEmail(?string $email): ?string
    {
        if ($email === null) {
            return null;
        }

        return filter_var(trim($email), FILTER_SANITIZE_EMAIL) ?: null;
    }

    /**
     * Sanitizar URL
     * 
     * @param string|null $url
     * @return string|null
     */
    public static function sanitizeUrl(?string $url): ?string
    {
        if ($url === null) {
            return null;
        }

        return filter_var(trim($url), FILTER_SANITIZE_URL) ?: null;
    }

    /**
     * Sanitizar número de teléfono
     * 
     * @param string|null $phone
     * @return string|null
     */
    public static function sanitizePhone(?string $phone): ?string
    {
        if ($phone === null) {
            return null;
        }

        // Permitir solo números, +, -, (, ), espacios
        return preg_replace('/[^0-9+\-() ]/', '', trim($phone));
    }

    /**
     * Encriptar datos sensibles
     * 
     * @param mixed $data
     * @return string
     */
    public static function encrypt($data): string
    {
        try {
            return Crypt::encryptString(json_encode($data));
        } catch (\Exception $e) {
            Log::error('Encryption failed: ' . $e->getMessage());
            throw new \RuntimeException('Failed to encrypt data');
        }
    }

    /**
     * Desencriptar datos
     * 
     * @param string $encrypted
     * @return mixed
     */
    public static function decrypt(string $encrypted)
    {
        try {
            return json_decode(Crypt::decryptString($encrypted), true);
        } catch (\Exception $e) {
            Log::error('Decryption failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Verificar si un usuario puede acceder a un recurso de un tenant
     * 
     * @param \App\Models\User $user
     * @param int $tenantId
     * @return bool
     */
    public static function canAccessTenantResource($user, int $tenantId): bool
    {
        // Super admin puede acceder a todo
        if ($user->role === 'super_admin') {
            return true;
        }

        // Tenant admin solo puede acceder a su propio tenant
        if ($user->role === 'tenant_admin') {
            return $user->tenant_id === $tenantId;
        }

        return false;
    }

    /**
     * Generar token seguro
     * 
     * @param int $length
     * @return string
     */
    public static function generateSecureToken(int $length = 32): string
    {
        return bin2hex(random_bytes($length));
    }

    /**
     * Validar que un array contenga solo los campos permitidos
     * 
     * @param array $data
     * @param array $allowedFields
     * @return array
     */
    public static function filterAllowedFields(array $data, array $allowedFields): array
    {
        return array_intersect_key($data, array_flip($allowedFields));
    }

    /**
     * Sanitizar array recursivamente
     * 
     * @param array $data
     * @return array
     */
    public static function sanitizeArray(array $data): array
    {
        return array_map(function ($value) {
            if (is_array($value)) {
                return self::sanitizeArray($value);
            }
            
            if (is_string($value)) {
                return self::sanitizeString($value);
            }
            
            return $value;
        }, $data);
    }

    /**
     * Verificar si un string contiene SQL injection attempts
     * 
     * @param string $input
     * @return bool
     */
    public static function containsSqlInjection(string $input): bool
    {
        $patterns = [
            '/(\bUNION\b|\bSELECT\b|\bINSERT\b|\bUPDATE\b|\bDELETE\b|\bDROP\b)/i',
            '/--/',
            '/;/',
            '/\/\*|\*\//',
            '/\bOR\b\s+\d+\s*=\s*\d+/i',
            '/\bAND\b\s+\d+\s*=\s*\d+/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $input)) {
                Log::warning('Potential SQL injection attempt detected', ['input' => $input]);
                return true;
            }
        }

        return false;
    }

    /**
     * Validar que un slug sea seguro
     * 
     * @param string $slug
     * @return bool
     */
    public static function isValidSlug(string $slug): bool
    {
        return (bool) preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug);
    }

    /**
     * Limpiar y validar color hexadecimal
     * 
     * @param string|null $color
     * @return string|null
     */
    public static function sanitizeHexColor(?string $color): ?string
    {
        if ($color === null) {
            return null;
        }

        // Remover espacios y convertir a minúsculas
        $color = strtolower(trim($color));

        // Agregar # si no lo tiene
        if (!str_starts_with($color, '#')) {
            $color = '#' . $color;
        }

        // Validar formato hexadecimal
        if (preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $color)) {
            return $color;
        }

        return null;
    }

    /**
     * Registrar intento de acceso no autorizado
     * 
     * @param \App\Models\User|null $user
     * @param string $resource
     * @param string $action
     */
    public static function logUnauthorizedAccess($user, string $resource, string $action): void
    {
        Log::warning('Unauthorized access attempt', [
            'user_id' => $user?->id,
            'user_email' => $user?->email,
            'resource' => $resource,
            'action' => $action,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
