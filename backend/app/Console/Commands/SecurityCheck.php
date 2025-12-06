<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SecurityCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'security:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica la configuración de seguridad de la aplicación';

    private $errors = [];
    private $warnings = [];
    private $passed = [];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔒 Iniciando verificación de seguridad...');
        $this->newLine();

        // Verificaciones
        $this->checkEnvironment();
        $this->checkDatabase();
        $this->checkFilePermissions();
        $this->checkSecurityHeaders();
        $this->checkAuthConfiguration();
        $this->checkPaymentGateways();
        $this->checkDependencies();

        // Mostrar resultados
        $this->displayResults();

        return $this->errors ? Command::FAILURE : Command::SUCCESS;
    }

    private function checkEnvironment()
    {
        $this->info('📋 Verificando configuración de entorno...');

        // APP_DEBUG debe ser false en producción
        if (config('app.debug') === true && config('app.env') === 'production') {
            $this->errors[] = 'APP_DEBUG está en true en producción. CRÍTICO: Cámbialo a false';
        } else {
            $this->passed[] = 'APP_DEBUG configurado correctamente';
        }

        // APP_ENV debe ser 'production'
        if (config('app.env') !== 'production') {
            $this->warnings[] = 'APP_ENV no está en "production". Actual: ' . config('app.env');
        } else {
            $this->passed[] = 'APP_ENV configurado correctamente';
        }

        // APP_KEY debe estar configurada
        if (!config('app.key')) {
            $this->errors[] = 'APP_KEY no está configurada. Ejecuta: php artisan key:generate';
        } else {
            $this->passed[] = 'APP_KEY configurada';
        }

        // LOG_LEVEL
        if (config('logging.level') === 'debug' && config('app.env') === 'production') {
            $this->warnings[] = 'LOG_LEVEL en debug. Recomendado: error o warning en producción';
        } else {
            $this->passed[] = 'LOG_LEVEL apropiado';
        }

        $this->newLine();
    }

    private function checkDatabase()
    {
        $this->info('💾 Verificando conexión a base de datos...');

        try {
            DB::connection()->getPdo();
            $this->passed[] = 'Conexión a base de datos exitosa';

            // Verificar que no se use 'root'
            $username = config('database.connections.mysql.username');
            if ($username === 'root') {
                $this->warnings[] = 'Usuario de BD es "root". Recomendado: crear usuario específico';
            } else {
                $this->passed[] = 'Usuario de BD no es "root"';
            }

            // Verificar tablas principales
            $tables = ['users', 'tenants', 'products', 'orders'];
            foreach ($tables as $table) {
                if (!\Schema::hasTable($table)) {
                    $this->warnings[] = "Tabla '$table' no existe. ¿Ejecutaste las migraciones?";
                }
            }

        } catch (\Exception $e) {
            $this->errors[] = 'No se puede conectar a la base de datos: ' . $e->getMessage();
        }

        $this->newLine();
    }

    private function checkFilePermissions()
    {
        $this->info('🔐 Verificando permisos de archivos...');

        $envPath = base_path('.env');
        
        if (File::exists($envPath)) {
            $perms = substr(sprintf('%o', fileperms($envPath)), -4);
            
            if ($perms !== '0644' && $perms !== '0600') {
                $this->warnings[] = ".env tiene permisos $perms. Recomendado: 644 o 600";
            } else {
                $this->passed[] = 'Permisos de .env correctos';
            }
        } else {
            $this->errors[] = 'Archivo .env no encontrado';
        }

        // Verificar storage
        $storagePath = storage_path();
        if (is_writable($storagePath)) {
            $this->passed[] = 'Directorio storage es escribible';
        } else {
            $this->errors[] = 'Directorio storage NO es escribible. Ejecuta: chmod -R 775 storage';
        }

        $this->newLine();
    }

    private function checkSecurityHeaders()
    {
        $this->info('🛡️ Verificando middlewares de seguridad...');

        $kernel = app(\Illuminate\Contracts\Http\Kernel::class);
        $middlewares = [
            \App\Http\Middleware\SecurityHeaders::class,
            \App\Http\Middleware\Cors::class,
        ];

        foreach ($middlewares as $middleware) {
            if (class_exists($middleware)) {
                $this->passed[] = 'Middleware ' . class_basename($middleware) . ' existe';
            } else {
                $this->warnings[] = 'Middleware ' . class_basename($middleware) . ' no encontrado';
            }
        }

        $this->newLine();
    }

    private function checkAuthConfiguration()
    {
        $this->info('🔑 Verificando configuración de autenticación...');

        // Sanctum
        if (config('sanctum.stateful')) {
            $this->passed[] = 'Sanctum configurado';
        } else {
            $this->warnings[] = 'Sanctum no está configurado correctamente';
        }

        // Session encryption
        if (config('session.encrypt') === true) {
            $this->passed[] = 'Sesiones encriptadas';
        } else {
            $this->warnings[] = 'SESSION_ENCRYPT está en false. Recomendado: true';
        }

        // Session lifetime
        $lifetime = config('session.lifetime');
        if ($lifetime > 120) {
            $this->warnings[] = "SESSION_LIFETIME es $lifetime minutos. Recomendado: máximo 120";
        } else {
            $this->passed[] = 'SESSION_LIFETIME apropiado';
        }

        $this->newLine();
    }

    private function checkPaymentGateways()
    {
        $this->info('💳 Verificando pasarelas de pago...');

        // Mercado Pago
        $mpKey = config('services.mercadopago.public_key');
        if ($mpKey && str_contains($mpKey, 'TEST')) {
            $this->warnings[] = 'Mercado Pago usa credenciales de TEST en producción';
        } elseif ($mpKey) {
            $this->passed[] = 'Mercado Pago configurado';
        }

        // Stripe
        $stripeKey = config('services.stripe.secret');
        if ($stripeKey && str_contains($stripeKey, 'sk_test')) {
            $this->warnings[] = 'Stripe usa credenciales de TEST en producción';
        } elseif ($stripeKey) {
            $this->passed[] = 'Stripe configurado';
        }

        // PayPal
        $paypalMode = config('services.paypal.mode');
        if ($paypalMode === 'sandbox' && config('app.env') === 'production') {
            $this->warnings[] = 'PayPal está en modo sandbox en producción';
        } elseif ($paypalMode) {
            $this->passed[] = 'PayPal configurado';
        }

        $this->newLine();
    }

    private function checkDependencies()
    {
        $this->info('📦 Verificando dependencias...');

        $composerLock = base_path('composer.lock');
        if (File::exists($composerLock)) {
            $this->passed[] = 'composer.lock existe';
        } else {
            $this->warnings[] = 'composer.lock no existe. Ejecuta: composer install';
        }

        // Verificar vendor
        $vendorDir = base_path('vendor');
        if (File::isDirectory($vendorDir)) {
            $this->passed[] = 'Directorio vendor existe';
        } else {
            $this->errors[] = 'Directorio vendor no existe. Ejecuta: composer install';
        }

        $this->newLine();
    }

    private function displayResults()
    {
        $this->newLine();
        $this->info('═══════════════════════════════════════');
        $this->info('         RESUMEN DE SEGURIDAD');
        $this->info('═══════════════════════════════════════');
        $this->newLine();

        // Errores críticos
        if (count($this->errors) > 0) {
            $this->error('❌ ERRORES CRÍTICOS (' . count($this->errors) . '):');
            foreach ($this->errors as $error) {
                $this->error('  • ' . $error);
            }
            $this->newLine();
        }

        // Advertencias
        if (count($this->warnings) > 0) {
            $this->warn('⚠️  ADVERTENCIAS (' . count($this->warnings) . '):');
            foreach ($this->warnings as $warning) {
                $this->warn('  • ' . $warning);
            }
            $this->newLine();
        }

        // Checks exitosos
        $this->info('✅ VERIFICACIONES EXITOSAS (' . count($this->passed) . '):');
        foreach ($this->passed as $pass) {
            $this->info('  • ' . $pass);
        }
        $this->newLine();

        // Score
        $total = count($this->errors) + count($this->warnings) + count($this->passed);
        $score = $total > 0 ? round((count($this->passed) / $total) * 100) : 0;

        $this->info('═══════════════════════════════════════');
        $this->info("PUNTUACIÓN DE SEGURIDAD: $score%");
        $this->info('═══════════════════════════════════════');
        $this->newLine();

        if (count($this->errors) > 0) {
            $this->error('⚠️  ACCIÓN REQUERIDA: Corrige los errores críticos antes de desplegar');
        } elseif (count($this->warnings) > 0) {
            $this->warn('⚠️  RECOMENDACIÓN: Revisa las advertencias antes de desplegar');
        } else {
            $this->info('✅ ¡Tu aplicación está lista para producción!');
        }

        $this->newLine();
    }
}
