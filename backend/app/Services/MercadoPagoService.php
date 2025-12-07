<?php

namespace App\Services;

use App\Models\Order;
use App\Models\PaymentGateway;
use MercadoPago\SDK;
use MercadoPago\Preference;
use MercadoPago\Item;
use MercadoPago\Payer;
use Exception;

class MercadoPagoService
{
    protected $gateway;

    public function __construct($tenantId)
    {
        $this->gateway = PaymentGateway::where('tenant_id', $tenantId)
            ->where('type', 'mercadopago')
            ->where('is_active', true)
            ->firstOrFail();

        $credentials = $this->gateway->credentials;
        SDK::setAccessToken($credentials['access_token']);
    }

    /**
     * Crear preferencia de pago
     */
    public function createPreference(Order $order, $backUrls = [])
    {
        try {
            $preference = new Preference();

            // Items de la orden
            $items = [];
            foreach ($order->items as $orderItem) {
                $item = new Item();
                $item->id = $orderItem->product_id;
                $item->title = $orderItem->product->name;
                $item->description = $orderItem->product->description ?? '';
                $item->quantity = $orderItem->quantity;
                $item->unit_price = floatval($orderItem->price);
                $item->currency_id = 'ARS'; // Cambiar según país
                
                $items[] = $item;
            }
            $preference->items = $items;

            // Información del pagador
            $payer = new Payer();
            $payer->name = $order->user->name;
            $payer->email = $order->user->email;
            $payer->phone = [
                'number' => $order->user->phone ?? '',
            ];
            $preference->payer = $payer;

            // URLs de retorno
            $preference->back_urls = [
                'success' => $backUrls['success'] ?? config('app.url') . '/payment/success',
                'failure' => $backUrls['failure'] ?? config('app.url') . '/payment/failure',
                'pending' => $backUrls['pending'] ?? config('app.url') . '/payment/pending',
            ];
            $preference->auto_return = 'approved';

            // Metadata
            $preference->external_reference = $order->order_number;
            $preference->metadata = [
                'order_id' => $order->id,
                'tenant_id' => $order->tenant_id,
            ];

            // Notificación
            $preference->notification_url = config('app.url') . '/api/v1/webhooks/mercadopago';

            // Modo sandbox
            if ($this->gateway->is_sandbox) {
                $preference->sandbox_mode = true;
            }

            $preference->save();

            return [
                'preference_id' => $preference->id,
                'init_point' => $preference->init_point,
                'sandbox_init_point' => $preference->sandbox_init_point,
            ];

        } catch (Exception $e) {
            \Log::error('MercadoPago Error: ' . $e->getMessage());
            throw new Exception('Error al crear preferencia de pago: ' . $e->getMessage());
        }
    }

    /**
     * Verificar pago
     */
    public function verifyPayment($paymentId)
    {
        try {
            $payment = \MercadoPago\Payment::find_by_id($paymentId);
            
            return [
                'status' => $payment->status,
                'status_detail' => $payment->status_detail,
                'amount' => $payment->transaction_amount,
                'external_reference' => $payment->external_reference,
                'payment_method' => $payment->payment_method_id,
            ];

        } catch (Exception $e) {
            \Log::error('MercadoPago Verify Error: ' . $e->getMessage());
            throw new Exception('Error al verificar pago');
        }
    }

    /**
     * Procesar webhook de MercadoPago
     */
    public function processWebhook($data)
    {
        try {
            if ($data['type'] === 'payment') {
                $payment = \MercadoPago\Payment::find_by_id($data['data']['id']);
                
                $orderNumber = $payment->external_reference;
                $order = Order::where('order_number', $orderNumber)->firstOrFail();

                // Actualizar estado según status de MP
                switch ($payment->status) {
                    case 'approved':
                        $order->update([
                            'payment_status' => 'paid',
                            'status' => 'processing',
                        ]);
                        break;
                    case 'pending':
                    case 'in_process':
                        $order->update(['payment_status' => 'pending']);
                        break;
                    case 'rejected':
                    case 'cancelled':
                        $order->update([
                            'payment_status' => 'failed',
                            'status' => 'cancelled',
                        ]);
                        break;
                }

                return true;
            }

            return false;

        } catch (Exception $e) {
            \Log::error('MercadoPago Webhook Error: ' . $e->getMessage());
            return false;
        }
    }
}
