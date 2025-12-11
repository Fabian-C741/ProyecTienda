<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Services\SecurityService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TenantProductController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Verificar que el usuario tenga tenant_id
        if (!$user->tenant_id) {
            return redirect()->route('dashboard.index')
                ->with('error', 'No tienes una tienda asignada. Contacta al administrador.');
        }
        
        $tenant = $user->tenant;
        
        if (!$tenant) {
            return redirect()->route('dashboard.index')
                ->with('error', 'Tu tienda no existe. Contacta al administrador.');
        }
        
        $products = Product::where('tenant_id', $tenant->id)
            ->with('category')
            ->latest()
            ->paginate(15);

        return view('tenant.products.index', compact('products', 'tenant'));
    }

    public function create()
    {
        $user = auth()->user();
        
        if (!$user->tenant_id || !$user->tenant) {
            return redirect()->route('vendedor.productos.index')
                ->with('error', 'No tienes una tienda asignada.');
        }
        
        $tenant = $user->tenant;
        $categories = Category::where('tenant_id', $tenant->id)->get();

        return view('tenant.products.create', compact('categories', 'tenant'));
    }

    public function store(Request $request)
    {
        $tenant = auth()->user()->tenant;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'compare_at_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'sku' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        // Sanitizar datos de entrada
        $validated['name'] = SecurityService::sanitizeString($validated['name']);
        $validated['description'] = SecurityService::sanitizeString($validated['description'] ?? null);

        // Mapear stock a stock_quantity para la base de datos
        $validated['stock_quantity'] = $validated['stock'];
        unset($validated['stock']);

        // Asegurar que el tenant_id sea del usuario autenticado
        $validated['tenant_id'] = $tenant->id;
        
        // Generar slug seguro
        $validated['slug'] = Str::slug($validated['name']);
        
        // Generar SKU si no se proporciona
        if (empty($validated['sku'])) {
            $validated['sku'] = 'PRD-' . strtoupper(Str::random(8));
        }

        // Verificar que la categoría pertenece al tenant (si se proporciona)
        if (!empty($validated['category_id'])) {
            $category = Category::find($validated['category_id']);
            if (!$category || $category->tenant_id !== $tenant->id) {
                return back()->withErrors(['category_id' => 'Categoría inválida'])->withInput();
            }
        }

        Product::create($validated);

        return redirect()->route('tenant.products.index')
            ->with('success', 'Producto creado exitosamente');
    }

    public function show(Product $product)
    {
        // Verificar acceso usando SecurityService
        if (!SecurityService::canAccessTenantResource(auth()->user(), $product->tenant_id)) {
            SecurityService::logUnauthorizedAccess(auth()->user(), 'product', 'view');
            abort(403, 'No tienes permiso para ver este producto.');
        }
        
        return view('tenant.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        // Verificar acceso
        if (!SecurityService::canAccessTenantResource(auth()->user(), $product->tenant_id)) {
            SecurityService::logUnauthorizedAccess(auth()->user(), 'product', 'edit');
            abort(403, 'No tienes permiso para editar este producto.');
        }
        
        $tenant = auth()->user()->tenant;
        $categories = Category::where('tenant_id', $tenant->id)->get();

        return view('tenant.products.edit', compact('product', 'categories', 'tenant'));
    }

    public function update(Request $request, Product $product)
    {
        // Verificar acceso
        if (!SecurityService::canAccessTenantResource(auth()->user(), $product->tenant_id)) {
            SecurityService::logUnauthorizedAccess(auth()->user(), 'product', 'update');
            abort(403, 'No tienes permiso para actualizar este producto.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'compare_at_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'sku' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        // Sanitizar datos
        $validated['name'] = SecurityService::sanitizeString($validated['name']);
        $validated['description'] = SecurityService::sanitizeString($validated['description'] ?? null);

        // Mapear stock a stock_quantity para la base de datos
        $validated['stock_quantity'] = $validated['stock'];
        unset($validated['stock']);

        // Verificar categoría
        if (!empty($validated['category_id'])) {
            $category = Category::find($validated['category_id']);
            if (!$category || $category->tenant_id !== auth()->user()->tenant_id) {
                return back()->withErrors(['category_id' => 'Categoría inválida'])->withInput();
            }
        }

        if ($product->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $product->update($validated);

        return redirect()->route('tenant.products.index')
            ->with('success', 'Producto actualizado exitosamente');
    }

    public function destroy(Product $product)
    {
        // Verificar acceso
        if (!SecurityService::canAccessTenantResource(auth()->user(), $product->tenant_id)) {
            SecurityService::logUnauthorizedAccess(auth()->user(), 'product', 'delete');
            abort(403, 'No tienes permiso para eliminar este producto.');
        }

        $product->delete();

        return redirect()->route('tenant.products.index')
            ->with('success', 'Producto eliminado exitosamente');
    }
}
