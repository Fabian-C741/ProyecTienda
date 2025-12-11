<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TenantSettingsController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if (!$user->tenant_id || !$user->tenant) {
            return redirect()->route('dashboard.index')
                ->with('error', 'No tienes una tienda asignada.');
        }
        
        $tenant = $user->tenant;
        
        return view('vendedor.configuracion.index', compact('tenant'));
    }

    public function updateStore(Request $request)
    {
        $tenant = auth()->user()->tenant;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
        ]);

        $tenant->update($validated);

        return back()->with('success', 'Información de la tienda actualizada');
    }

    public function updateAppearance(Request $request)
    {
        $tenant = auth()->user()->tenant;

        $validated = $request->validate([
            'primary_color' => 'nullable|string|max:7',
            'secondary_color' => 'nullable|string|max:7',
            'accent_color' => 'nullable|string|max:7',
            'font_family' => 'nullable|string|max:100',
            'hero_title' => 'nullable|string|max:255',
            'hero_subtitle' => 'nullable|string|max:500',
            'hero_button_text' => 'nullable|string|max:100',
            'facebook_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'whatsapp_number' => 'nullable|string|max:50',
        ]);

        $tenant->update($validated);

        return back()->with('success', 'Apariencia de la tienda actualizada');
    }

    public function updateMercadoPago(Request $request)
    {
        $tenant = auth()->user()->tenant;

        $validated = $request->validate([
            'mercadopago_public_key' => 'nullable|string',
            'mercadopago_access_token' => 'nullable|string',
        ]);

        $tenant->update($validated);

        return back()->with('success', 'Configuración de MercadoPago actualizada');
    }

    public function testMercadoPago(Request $request)
    {
        $tenant = auth()->user()->tenant;

        if (!$tenant->mercadopago_public_key || !$tenant->mercadopago_access_token) {
            return back()->with('error', 'Debes configurar las credenciales primero');
        }

        // Aquí podrías hacer una prueba real con la API de MercadoPago
        // Por ahora solo validamos que existan las credenciales

        return back()->with('success', 'Credenciales configuradas correctamente');
    }
}
