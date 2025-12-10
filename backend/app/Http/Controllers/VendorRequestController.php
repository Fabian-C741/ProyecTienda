<?php

namespace App\Http\Controllers;

use App\Models\VendorRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VendorRequestController extends Controller
{
    /**
     * Mostrar formulario de solicitud de tienda
     */
    public function showForm()
    {
        return view('vendor-request');
    }

    /**
     * Procesar solicitud de tienda
     */
    public function submitRequest(Request $request)
    {
        $validated = $request->validate([
            'store_name' => 'required|string|max:255',
            'slug' => 'required|string|max:100|unique:vendor_requests,slug|unique:tenants,slug|regex:/^[a-z0-9-]+$/',
            'owner_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:vendor_requests,email',
            'phone' => 'required|string|max:20',
            'category' => 'required|string|max:100',
            'description' => 'required|string|min:50',
            'terms' => 'accepted',
        ], [
            'slug.regex' => 'El subdominio solo puede contener letras minúsculas, números y guiones',
            'slug.unique' => 'Este subdominio ya está en uso. Por favor elige otro',
            'email.unique' => 'Ya existe una solicitud con este email',
            'description.min' => 'La descripción debe tener al menos 50 caracteres',
        ]);

        VendorRequest::create([
            'store_name' => $validated['store_name'],
            'slug' => strtolower($validated['slug']),
            'owner_name' => $validated['owner_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'status' => 'pending',
        ]);

        return back()->with('success', '¡Solicitud enviada exitosamente! Te contactaremos pronto a ' . $validated['email']);
    }
}
