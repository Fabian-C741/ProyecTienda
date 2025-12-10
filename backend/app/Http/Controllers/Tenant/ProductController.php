<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $tenant = auth()->user()->tenant;
        $products = Product::where('tenant_id', $tenant->id)
            ->with('category')
            ->latest()
            ->paginate(20);

        return view('tenant.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all(); // Get all categories (not tenant-specific)

        return view('tenant.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $tenant = auth()->user()->tenant;

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'nullable|string|unique:products,sku',
            'featured_image' => 'nullable|image|max:2048',
            'track_inventory' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $data = $request->except('featured_image');
        $data['tenant_id'] = $tenant->id;
        $data['slug'] = Str::slug($request->name) . '-' . Str::random(6);
        $data['track_inventory'] = $request->boolean('track_inventory', true);
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('tenant.products.index')->with('success', 'Producto creado correctamente');
    }

    public function edit(Product $product)
    {
        $this->authorize('update', $product);
        
        $categories = Category::all(); // Get all categories

        return view('tenant.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'nullable|string|unique:products,sku,' . $product->id,
            'featured_image' => 'nullable|image|max:2048',
            'track_inventory' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $data = $request->except('featured_image');
        $data['track_inventory'] = $request->boolean('track_inventory');
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('featured_image')) {
            if ($product->featured_image) {
                Storage::disk('public')->delete($product->featured_image);
            }
            $data['featured_image'] = $request->file('featured_image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('tenant.products.index')->with('success', 'Producto actualizado correctamente');
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        if ($product->featured_image) {
            Storage::disk('public')->delete($product->featured_image);
        }

        $product->delete();

        return back()->with('success', 'Producto eliminado correctamente');
    }
}
