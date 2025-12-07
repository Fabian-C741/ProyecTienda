<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use Illuminate\Http\Request;

class PaymentGatewayWebController extends Controller
{
    public function index()
    {
        $gateways = PaymentGateway::all();
        return view('admin.payment-gateways.index', compact('gateways'));
    }

    public function edit(PaymentGateway $paymentGateway)
    {
        return view('admin.payment-gateways.edit', compact('paymentGateway'));
    }

    public function update(Request $request, PaymentGateway $paymentGateway)
    {
        $validated = $request->validate([
            'is_active' => 'boolean',
            'api_key' => 'nullable|string',
            'api_secret' => 'nullable|string',
        ]);

        $paymentGateway->update($validated);

        return redirect()->route('admin.payment-gateways.index')
            ->with('success', 'Pasarela de pago actualizada exitosamente');
    }
}
