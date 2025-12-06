<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index(Request $request)
    {
        $query = Tenant::active();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $tenants = $query->orderBy('name')->paginate(20);

        return response()->json($tenants);
    }

    public function show($slug)
    {
        $tenant = Tenant::where('slug', $slug)
            ->active()
            ->firstOrFail();

        return response()->json([
            'tenant' => $tenant,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:tenants,slug',
            'domain' => 'nullable|string|unique:tenants,domain',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $tenant = Tenant::create($request->all());

        return response()->json([
            'message' => 'Tenant creado exitosamente',
            'tenant' => $tenant,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $tenant = Tenant::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|unique:tenants,slug,' . $tenant->id,
            'domain' => 'nullable|string|unique:tenants,domain,' . $tenant->id,
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $tenant->update($request->all());

        return response()->json([
            'message' => 'Tenant actualizado exitosamente',
            'tenant' => $tenant,
        ]);
    }

    public function destroy($id)
    {
        $tenant = Tenant::findOrFail($id);
        $tenant->delete();

        return response()->json([
            'message' => 'Tenant eliminado exitosamente',
        ]);
    }

    public function activate($id)
    {
        $tenant = Tenant::findOrFail($id);
        $tenant->update(['is_active' => true]);

        return response()->json([
            'message' => 'Tenant activado exitosamente',
            'tenant' => $tenant,
        ]);
    }

    public function deactivate($id)
    {
        $tenant = Tenant::findOrFail($id);
        $tenant->update(['is_active' => false]);

        return response()->json([
            'message' => 'Tenant desactivado exitosamente',
            'tenant' => $tenant,
        ]);
    }
}
