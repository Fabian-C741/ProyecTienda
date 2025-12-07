<?php
// Script para crear pasarelas de pago iniciales

use App\Models\PaymentGateway;

PaymentGateway::create([
    'name' => 'stripe',
    'display_name' => 'Stripe',
    'is_active' => false,
    'is_test_mode' => true,
    'credentials' => [],
    'settings' => [],
]);

PaymentGateway::create([
    'name' => 'paypal',
    'display_name' => 'PayPal',
    'is_active' => false,
    'is_test_mode' => true,
    'credentials' => [],
    'settings' => [],
]);

PaymentGateway::create([
    'name' => 'mercadopago',
    'display_name' => 'Mercado Pago',
    'is_active' => false,
    'is_test_mode' => true,
    'credentials' => [],
    'settings' => [],
]);

echo "Pasarelas de pago creadas exitosamente\n";
