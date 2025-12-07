<?php

namespace Database\Seeders;

use App\Models\PaymentGateway;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class PaymentGatewaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el tenant demo
        $tenant = Tenant::where('slug', 'tienda-demo')->first();

        if (!$tenant) {
            $this->command->warn('No se encontró el tenant demo. Ejecuta primero DemoDataSeeder');
            return;
        }

        // Mercado Pago (Sandbox)
        PaymentGateway::updateOrCreate(
            [
                'tenant_id' => $tenant->id,
                'name' => 'mercadopago',
            ],
            [
                'credentials' => [
                    'access_token' => 'TEST-1234567890-123456-abcdef1234567890-123456789', // Token de prueba
                    'public_key' => 'TEST-abcdef12-3456-7890-abcd-ef1234567890',
                ],
                'is_active' => true,
                'is_sandbox' => true,
            ]
        );

        $this->command->info('✓ Mercado Pago configurado (sandbox)');

        // Stripe (Test mode)
        PaymentGateway::updateOrCreate(
            [
                'tenant_id' => $tenant->id,
                'name' => 'stripe',
            ],
            [
                'credentials' => [
                    'secret_key' => 'sk_test_1234567890abcdefghijklmnopqrstuv',
                    'publishable_key' => 'pk_test_1234567890abcdefghijklmnopqrstuv',
                    'webhook_secret' => 'whsec_1234567890abcdefghijklmnopqrstuv',
                ],
                'is_active' => true,
                'is_sandbox' => true,
            ]
        );

        $this->command->info('✓ Stripe configurado (test mode)');

        // PayPal (Sandbox)
        PaymentGateway::updateOrCreate(
            [
                'tenant_id' => $tenant->id,
                'name' => 'paypal',
            ],
            [
                'credentials' => [
                    'client_id' => 'AbCdEf1234567890GhIjKlMnOpQrStUvWxYz1234567890',
                    'client_secret' => 'AbCdEf1234567890GhIjKlMnOpQrStUvWxYz1234567890',
                ],
                'is_active' => false, // Desactivado por defecto
                'is_sandbox' => true,
            ]
        );

        $this->command->info('✓ PayPal configurado (sandbox, desactivado)');

        $this->command->info('');
        $this->command->info('═══════════════════════════════════════════════');
        $this->command->info('  GATEWAYS DE PAGO CONFIGURADOS');
        $this->command->info('═══════════════════════════════════════════════');
        $this->command->warn('⚠️  IMPORTANTE: Estas son credenciales de PRUEBA');
        $this->command->warn('⚠️  Para producción, actualiza con tus credenciales reales');
        $this->command->info('');
        $this->command->info('Mercado Pago: https://www.mercadopago.com.ar/developers');
        $this->command->info('Stripe: https://dashboard.stripe.com/test/apikeys');
        $this->command->info('PayPal: https://developer.paypal.com/dashboard');
        $this->command->info('═══════════════════════════════════════════════');
    }
}
