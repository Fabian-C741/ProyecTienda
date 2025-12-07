<?php

namespace App\Services;

use App\Models\Order;
use App\Models\PaymentGateway;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Webhook;
use Exception;

class StripeService
{
    protected $gateway;

    public function __construct($tenantId)
    {
        $this->gateway = PaymentGateway::where('tenant_id', $tenantId)
            ->where('type', 'stripe')
            ->where('is_active', true)
            ->firstOrFail();

        $credentials = $this->gateway->credentials;
        Stripe::setApiKey($credentials['secret_key']);
    }

    /**
     * Crear sesión de checkout
     */
    public function createCheckoutSession(Order $order, $successUrl, $cancelUrl)
    {
        try {
            $lineItems = [];

            foreach ($order->items as $orderItem) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'usd', // Cambiar según necesidad
                        'product_data' => [
                            'name' => $orderItem->product->name,
                            'description' => $orderItem->product->description ?? '',
                            'images' => $orderItem->product->images ? [($orderItem->product->images[0] ?? '')] : [],
                        ],
                        'unit_amount' => intval($orderItem->price * 100), // Centavos
                    ],
                    'quantity' => $orderItem->quantity,
                ];
            }

            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => $successUrl . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => $cancelUrl,
                'customer_email' => $order->user->email,
                'client_reference_id' => $order->order_number,
                'metadata' => [
                    'order_id' => $order->id,
                    'tenant_id' => $order->tenant_id,
                ],
            ]);

            return [
                'session_id' => $session->id,
                'checkout_url' => $session->url,
            ];

        } catch (Exception $e) {
            \Log::error('Stripe Error: ' . $e->getMessage());
            throw new Exception('Error al crear sesión de pago: ' . $e->getMessage());
        }
    }

    /**
     * Verificar sesión de pago
     */
    public function verifySession($sessionId)
    {
        try {
            $session = Session::retrieve($sessionId);

            return [
                'status' => $session->payment_status,
                'amount' => $session->amount_total / 100,
                'reference' => $session->client_reference_id,
            ];

        } catch (Exception $e) {
            \Log::error('Stripe Verify Error: ' . $e->getMessage());
            throw new Exception('Error al verificar sesión de pago');
        }
    }

    /**
     * Procesar webhook de Stripe
     */
    public function processWebhook($payload, $signature)
    {
        try {
            $credentials = $this->gateway->credentials;
            $event = Webhook::constructEvent(
                $payload,
                $signature,
                $credentials['webhook_secret']
            );

            switch ($event->type) {
                case 'checkout.session.completed':
                    $session = $event->data->object;
                    $orderNumber = $session->client_reference_id;
                    $order = Order::where('order_number', $orderNumber)->firstOrFail();

                    if ($session->payment_status === 'paid') {
                        $order->update([
                            'payment_status' => 'paid',
                            'status' => 'processing',
                        ]);
                    }
                    break;

                case 'checkout.session.async_payment_succeeded':
                    $session = $event->data->object;
                    $orderNumber = $session->client_reference_id;
                    $order = Order::where('order_number', $orderNumber)->firstOrFail();

                    $order->update([
                        'payment_status' => 'paid',
                        'status' => 'processing',
                    ]);
                    break;

                case 'checkout.session.async_payment_failed':
                    $session = $event->data->object;
                    $orderNumber = $session->client_reference_id;
                    $order = Order::where('order_number', $orderNumber)->firstOrFail();

                    $order->update([
                        'payment_status' => 'failed',
                        'status' => 'cancelled',
                    ]);
                    break;
            }

            return true;

        } catch (Exception $e) {
            \Log::error('Stripe Webhook Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Crear reembolso
     */
    public function createRefund($paymentIntentId, $amount = null)
    {
        try {
            $refundData = ['payment_intent' => $paymentIntentId];
            
            if ($amount) {
                $refundData['amount'] = intval($amount * 100);
            }

            $refund = \Stripe\Refund::create($refundData);

            return [
                'refund_id' => $refund->id,
                'status' => $refund->status,
                'amount' => $refund->amount / 100,
            ];

        } catch (Exception $e) {
            \Log::error('Stripe Refund Error: ' . $e->getMessage());
            throw new Exception('Error al crear reembolso');
        }
    }
}
