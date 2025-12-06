<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HealthController extends Controller
{
    /**
     * Health check endpoint
     * 
     * Verifica que la API esté funcionando y la BD conectada
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function check()
    {
        $health = [
            'status' => 'ok',
            'timestamp' => now()->toIso8601String(),
            'environment' => config('app.env'),
        ];

        // Verificar conexión a BD
        try {
            DB::connection()->getPdo();
            $health['database'] = 'connected';
            
            // Verificar algunas tablas críticas
            $tables = ['users', 'tenants', 'products'];
            $missingTables = [];
            
            foreach ($tables as $table) {
                if (!\Schema::hasTable($table)) {
                    $missingTables[] = $table;
                }
            }
            
            if (!empty($missingTables)) {
                $health['database_warning'] = 'Missing tables: ' . implode(', ', $missingTables);
            }
            
        } catch (\Exception $e) {
            $health['database'] = 'disconnected';
            $health['database_error'] = 'Cannot connect to database';
            $health['status'] = 'error';
        }

        // Verificar storage escribible
        $storagePath = storage_path('logs');
        $health['storage'] = is_writable($storagePath) ? 'writable' : 'read-only';

        // Verificar cache
        try {
            \Cache::put('health_check', true, 10);
            $health['cache'] = \Cache::get('health_check') ? 'working' : 'not-working';
        } catch (\Exception $e) {
            $health['cache'] = 'error';
        }

        $statusCode = $health['status'] === 'ok' ? 200 : 503;

        return response()->json($health, $statusCode);
    }

    /**
     * Información de la versión
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function version()
    {
        return response()->json([
            'app_name' => config('app.name'),
            'version' => '1.0.0',
            'laravel_version' => app()->version(),
            'php_version' => PHP_VERSION,
        ]);
    }
}
