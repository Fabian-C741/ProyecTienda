<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PaymentGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Crear preferencia de pago con Mercado Pago
     */
    public function createMercadoPago(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        $order = Order::with('items')->findOrFail($request->order_id);

        $gateway = PaymentGateway::where('tenant_id', $order->tenant_id)
            ->where('name', 'mercadopago')
            ->where('is_active', true)
            ->firstOrFail();

        try {
            \MercadoPago\SDK::setAccessToken($gateway->credentials['access_token']);

            $preference = new \MercadoPago\Preference();

            // Items
            $items = [];
            foreach ($order->items as $item) {
                $mpItem = new \MercadoPago\Item();
                $mpItem->title = $item->product_name;
                $mpItem->quantity = $item->quantity;
                $mpItem->unit_price = (float) $item->price;
                $items[] = $mpItem;
            }
            $preference->items = $items;

            // Configuración
            $preference->external_reference = $order->order_number;
            $preference->back_urls = [
                "success" => config('app.frontend_url') . "/checkout/success",
                "failure" => config('app.frontend_url') . "/checkout/failure",
                "pending" => config('app.frontend_url') . "/checkout/pending",
            ];
            $preference->auto_return = "approved";
            $preference->notification_url = config('app.url') . "/api/v1/webhooks/mercadopago";

            $preference->save();

            $order->update([
                'payment_gateway' => 'mercadopago',
                'metadata' => array_merge($order->metadata ?? [], [
                    'preference_id' => $preference->id,
                ]),
            ]);

            return response()->json([
                'preference_id' => $preference->id,
                'init_point' => $preference->init_point,
                'sandbox_init_point' => $preference->sandbox_init_point,
            ]);

        } catch (\Exception $e) {
            Log::error('MercadoPago Error: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'Error al crear la preferencia de pago',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Webhook de Mercado Pago
     */
    public function webhookMercadoPago(Request $request)
    {
        Log::info('MercadoPago Webhook', $request->all());

        if ($request->type === 'payment') {
            $paymentId = $request->input('data.id');

            try {
                // Obtener información del pago
                $gateway = PaymentGateway::where('name', 'mercadopago')
                    ->where('is_active', true)
                    ->first();

                if (!$gateway) {
                    return response()->json(['status' => 'error'], 404);
                }

                \MercadoPago\SDK::setAccessToken($gateway->credentials['access_token']);
                $payment = \MercadoPago\Payment::find_by_id($paymentId);

                if ($payment->status === 'approved') {
                    $order = Order::where('order_number', $payment->external_reference)->first();
                    
                    if ($order && $order->payment_status !== 'paid') {
                        $order->markAsPaid($payment->id);
                        $order->update(['status' => 'processing']);
                    }
                }

            } catch (\Exception $e) {
                Log::error('MercadoPago Webhook Error: ' . $e->getMessage());
            }
        }

        return response()->json(['status' => 'ok']);
    }

    /**
     * Crear Payment Intent con Stripe
     */
    public function createStripe(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        $order = Order::findOrFail($request->order_id);

        $gateway = PaymentGateway::where('tenant_id', $order->tenant_id)
            ->where('name', 'stripe')
            ->where('is_active', true)
            ->firstOrFail();

        try {
            \Stripe\Stripe::setApiKey($gateway->credentials['secret_key']);

            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $order->total * 100, // En centavos
                'currency' => strtolower($order->currency),
                'metadata' => [
                    'order_number' => $order->order_number,
                    'tenant_id' => $order->tenant_id,
                ],
                'description' => 'Orden ' . $order->order_number,
            ]);

            $order->update([
                'payment_gateway' => 'stripe',
                'metadata' => array_merge($order->metadata ?? [], [
                    'payment_intent_id' => $paymentIntent->id,
                ]),
            ]);

            return response()->json([
                'client_secret' => $paymentIntent->client_secret,
                'public_key' => $gateway->credentials['public_key'],
            ]);

        } catch (\Exception $e) {
            Log::error('Stripe Error: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'Error al crear el pago',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Webhook de Stripe
     */
    public function webhookStripe(Request $request)
    {
        $gateway = PaymentGateway::where('name', 'stripe')
            ->where('is_active', true)
            ->first();

        if (!$gateway) {
            return response()->json(['error' => 'Gateway not found'], 404);
        }

        \Stripe\Stripe::setApiKey($gateway->credentials['secret_key']);

        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = $gateway->credentials['webhook_secret'] ?? '';

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $webhookSecret);

            if ($event->type === 'payment_intent.succeeded') {
                $paymentIntent = $event->data->object;
                $orderNumber = $paymentIntent->metadata->order_number;

                $order = Order::where('order_number', $orderNumber)->first();
                
                if ($order && $order->payment_status !== 'paid') {
                    $order->markAsPaid($paymentIntent->id);
                    $order->update(['status' => 'processing']);
                }
            }

        } catch (\Exception $e) {
            Log::error('Stripe Webhook Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Crear orden de PayPal
     */
    public function createPayPal(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        $order = Order::with('items')->findOrFail($request->order_id);

        $gateway = PaymentGateway::where('tenant_id', $order->tenant_id)
            ->where('name', 'paypal')
            ->where('is_active', true)
            ->firstOrFail();

        try {
            $apiContext = new \PayPal\Rest\ApiContext(
                new \PayPal\Auth\OAuthTokenCredential(
                    $gateway->credentials['client_id'],
                    $gateway->credentials['secret']
                )
            );

            $apiContext->setConfig([
                'mode' => $gateway->is_test_mode ? 'sandbox' : 'live',
            ]);

            $payer = new \PayPal\Api\Payer();
            $payer->setPaymentMethod('paypal');

            $items = [];
            foreach ($order->items as $item) {
                $paypalItem = new \PayPal\Api\Item();
                $paypalItem->setName($item->product_name)
                    ->setCurrency($order->currency)
                    ->setQuantity($item->quantity)
                    ->setPrice($item->price);
                $items[] = $paypalItem;
            }

            $itemList = new \PayPal\Api\ItemList();
            $itemList->setItems($items);

            $amount = new \PayPal\Api\Amount();
            $amount->setCurrency($order->currency)
                ->setTotal($order->total);

            $transaction = new \PayPal\Api\Transaction();
            $transaction->setAmount($amount)
                ->setItemList($itemList)
                ->setDescription('Orden ' . $order->order_number)
                ->setInvoiceNumber($order->order_number);

            $redirectUrls = new \PayPal\Api\RedirectUrls();
            $redirectUrls->setReturnUrl(config('app.frontend_url') . '/checkout/success')
                ->setCancelUrl(config('app.frontend_url') . '/checkout/cancel');

            $payment = new \PayPal\Api\Payment();
            $payment->setIntent('sale')
                ->setPayer($payer)
                ->setRedirectUrls($redirectUrls)
                ->setTransactions([$transaction]);

            $payment->create($apiContext);

            $order->update([
                'payment_gateway' => 'paypal',
                'metadata' => array_merge($order->metadata ?? [], [
                    'paypal_payment_id' => $payment->getId(),
                ]),
            ]);

            return response()->json([
                'approval_url' => $payment->getApprovalLink(),
                'payment_id' => $payment->getId(),
            ]);

        } catch (\Exception $e) {
            Log::error('PayPal Error: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'Error al crear el pago',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Webhook de PayPal
     */
    public function webhookPayPal(Request $request)
    {
        Log::info('PayPal Webhook', $request->all());

        // Implementar validación de webhook de PayPal según documentación oficial
        
        return response()->json(['status' => 'ok']);
    }
}
