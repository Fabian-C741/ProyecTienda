<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TenantProductController extends Controller
{
    public function index()
    {
        $tenant = auth()->user()->tenant;
        
        $products = Product::where('tenant_id', $tenant->id)
            ->with('category')
            ->latest()
            ->paginate(15);

        return view('tenant.products.index', compact('products', 'tenant'));
    }

    public function create()
    {
        $tenant = auth()->user()->tenant;
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

        // Mapear stock a stock_quantity para la base de datos
        $validated['stock_quantity'] = $validated['stock'];
        unset($validated['stock']);

        $validated['tenant_id'] = $tenant->id;
        $validated['slug'] = Str::slug($validated['name']);
        
        // Generar SKU si no se proporciona
        if (empty($validated['sku'])) {
            $validated['sku'] = 'PRD-' . strtoupper(Str::random(8));
        }

        Product::create($validated);

        return redirect()->route('tenant.products.index')
            ->with('success', 'Producto creado exitosamente');
    }

    public function show(Product $product)
    {
        $this->authorize('view', $product);
        
        return view('tenant.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $this->authorize('update', $product);
        
        $tenant = auth()->user()->tenant;
        $categories = Category::where('tenant_id', $tenant->id)->get();

        return view('tenant.products.edit', compact('product', 'categories', 'tenant'));
    }

    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

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

        // Mapear stock a stock_quantity para la base de datos
        $validated['stock_quantity'] = $validated['stock'];
        unset($validated['stock']);

        if ($product->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $product->update($validated);

        return redirect()->route('tenant.products.index')
            ->with('success', 'Producto actualizado exitosamente');
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        $product->delete();

        return redirect()->route('tenant.products.index')
            ->with('success', 'Producto eliminado exitosamente');
    }
}
