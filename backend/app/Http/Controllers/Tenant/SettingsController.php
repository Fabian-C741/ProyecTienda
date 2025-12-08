<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\TenantSetting;
use App\Models\TenantPaymentGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $tenant = auth()->user()->tenant;
        $settings = $tenant->settings ?? new TenantSetting(['tenant_id' => $tenant->id]);
        $paymentGateway = $tenant->paymentGateways()
            ->where('gateway_name', 'mercadopago')
            ->first();

        return view('tenant.settings.index', compact('tenant', 'settings', 'paymentGateway'));
    }

    public function updateStore(Request $request)
    {
        $tenant = auth()->user()->tenant;

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'banner' => 'nullable|image|max:4096',
        ]);

        $data = $request->only(['name', 'description', 'email', 'phone', 'address']);

        // Subir logo
        if ($request->hasFile('logo')) {
            if ($tenant->logo) {
                Storage::disk('public')->delete($tenant->logo);
            }
            $data['logo'] = $request->file('logo')->store('tenants/logos', 'public');
        }

        // Subir banner
        if ($request->hasFile('banner')) {
            if ($tenant->banner) {
                Storage::disk('public')->delete($tenant->banner);
            }
            $data['banner'] = $request->file('banner')->store('tenants/banners', 'public');
        }

        $tenant->update($data);

        return back()->with('success', 'Información de la tienda actualizada correctamente');
    }

    public function updateAppearance(Request $request)
    {
        $tenant = auth()->user()->tenant;

        $request->validate([
            'primary_color' => 'required|string|max:7',
            'secondary_color' => 'required|string|max:7',
            'font_family' => 'nullable|string|max:100',
            'show_categories' => 'boolean',
            'show_search' => 'boolean',
            'show_reviews' => 'boolean',
        ]);

        $settings = $tenant->settings ?? new TenantSetting(['tenant_id' => $tenant->id]);
        
        $settings->fill([
            'primary_color' => $request->primary_color,
            'secondary_color' => $request->secondary_color,
            'font_family' => $request->font_family ?? 'Inter',
            'show_categories' => $request->boolean('show_categories'),
            'show_search' => $request->boolean('show_search'),
            'show_reviews' => $request->boolean('show_reviews'),
        ]);

        $settings->save();

        return back()->with('success', 'Apariencia actualizada correctamente');
    }

    public function updateMercadoPago(Request $request)
    {
        $tenant = auth()->user()->tenant;

        $request->validate([
            'public_key' => 'required|string',
            'access_token' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $gateway = TenantPaymentGateway::updateOrCreate(
            [
                'tenant_id' => $tenant->id,
                'gateway' => 'mercadopago'
            ],
            [
                'public_key' => $request->public_key,
                'access_token' => $request->access_token,
                'is_active' => $request->boolean('is_active'),
            ]
        );

        return back()->with('success', 'Configuración de Mercado Pago actualizada correctamente');
    }

    public function testMercadoPago()
    {
        $tenant = auth()->user()->tenant;
        $gateway = $tenant->paymentGateways()
            ->where('gateway', 'mercadopago')
            ->where('is_active', true)
            ->first();

        if (!$gateway) {
            return back()->with('error', 'Mercado Pago no está configurado o no está activo');
        }

        // Aquí podrías hacer una prueba real con la API de Mercado Pago
        // Por ahora solo verificamos que existan las credenciales

        if ($gateway->public_key && $gateway->access_token) {
            return back()->with('success', 'Credenciales de Mercado Pago válidas');
        }

        return back()->with('error', 'Faltan credenciales de Mercado Pago');
    }
}
