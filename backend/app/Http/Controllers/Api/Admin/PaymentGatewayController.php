<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentGatewayController extends Controller
{
    /**
     * Listar gateways de pago
     */
    public function index(Request $request)
    {
        $query = PaymentGateway::query();

        if (!$request->user()->hasRole('super_admin')) {
            $query->where('tenant_id', $request->user()->tenant_id);
        }

        $gateways = $query->get()->map(function ($gateway) {
            // Ocultar credenciales sensibles
            return [
                'id' => $gateway->id,
                'tenant_id' => $gateway->tenant_id,
                'name' => $gateway->name,
                'is_active' => $gateway->is_active,
                'is_sandbox' => $gateway->is_sandbox,
                'created_at' => $gateway->created_at,
                'updated_at' => $gateway->updated_at,
                'has_credentials' => !empty($gateway->credentials),
            ];
        });

        return response()->json($gateways);
    }

    /**
     * Mostrar un gateway específico
     */
    public function show(Request $request, $id)
    {
        $gateway = PaymentGateway::findOrFail($id);

        if (!$request->user()->hasRole('super_admin') && $gateway->tenant_id != $request->user()->tenant_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        return response()->json([
            'id' => $gateway->id,
            'tenant_id' => $gateway->tenant_id,
            'name' => $gateway->name,
            'is_active' => $gateway->is_active,
            'is_sandbox' => $gateway->is_sandbox,
            'created_at' => $gateway->created_at,
            'updated_at' => $gateway->updated_at,
            'credentials' => $gateway->credentials, // Solo mostrar a autorizados
        ]);
    }

    /**
     * Crear un gateway de pago
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|in:mercadopago,stripe,paypal',
            'credentials' => 'required|array',
            'is_active' => 'boolean',
            'is_sandbox' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Validar credenciales según el gateway
        $credentialsValidator = $this->validateCredentials($request->name, $request->credentials);
        if ($credentialsValidator !== true) {
            return response()->json(['errors' => $credentialsValidator], 422);
        }

        $gateway = PaymentGateway::create([
            'tenant_id' => $request->user()->tenant_id,
            'name' => $request->name,
            'credentials' => $request->credentials,
            'is_active' => $request->is_active ?? true,
            'is_sandbox' => $request->is_sandbox ?? true,
        ]);

        return response()->json([
            'message' => 'Gateway de pago creado exitosamente',
            'gateway' => [
                'id' => $gateway->id,
                'name' => $gateway->name,
                'is_active' => $gateway->is_active,
                'is_sandbox' => $gateway->is_sandbox,
            ],
        ], 201);
    }

    /**
     * Actualizar un gateway de pago
     */
    public function update(Request $request, $id)
    {
        $gateway = PaymentGateway::findOrFail($id);

        if (!$request->user()->hasRole('super_admin') && $gateway->tenant_id != $request->user()->tenant_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'credentials' => 'sometimes|array',
            'is_active' => 'boolean',
            'is_sandbox' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->has('credentials')) {
            $credentialsValidator = $this->validateCredentials($gateway->name, $request->credentials);
            if ($credentialsValidator !== true) {
                return response()->json(['errors' => $credentialsValidator], 422);
            }
        }

        $gateway->update($request->only(['credentials', 'is_active', 'is_sandbox']));

        return response()->json([
            'message' => 'Gateway actualizado exitosamente',
            'gateway' => [
                'id' => $gateway->id,
                'name' => $gateway->name,
                'is_active' => $gateway->is_active,
                'is_sandbox' => $gateway->is_sandbox,
            ],
        ]);
    }

    /**
     * Eliminar un gateway de pago
     */
    public function destroy(Request $request, $id)
    {
        $gateway = PaymentGateway::findOrFail($id);

        if (!$request->user()->hasRole('super_admin') && $gateway->tenant_id != $request->user()->tenant_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $gateway->delete();

        return response()->json([
            'message' => 'Gateway eliminado exitosamente'
        ]);
    }

    /**
     * Validar credenciales según el gateway
     */
    private function validateCredentials($gatewayName, $credentials)
    {
        switch ($gatewayName) {
            case 'mercadopago':
                if (!isset($credentials['access_token'])) {
                    return ['credentials.access_token' => ['El access_token es requerido']];
                }
                break;

            case 'stripe':
                if (!isset($credentials['secret_key']) || !isset($credentials['webhook_secret'])) {
                    return ['credentials' => ['secret_key y webhook_secret son requeridos']];
                }
                break;

            case 'paypal':
                if (!isset($credentials['client_id']) || !isset($credentials['client_secret'])) {
                    return ['credentials' => ['client_id y client_secret son requeridos']];
                }
                break;
        }

        return true;
    }
}
