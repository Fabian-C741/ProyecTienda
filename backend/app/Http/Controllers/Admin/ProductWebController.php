<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductWebController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category']);
        
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }
        
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        
        if ($request->filled('status')) {
            $query->where('is_active', $request->status == 'active');
        }
        
        $products = $query->latest()->paginate(10);
        $categories = Category::where('is_active', true)->get();
        
        return view('admin.products.index', compact('products', 'categories'));
    }
    
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        $products = Product::where('name', 'like', '%' . $query . '%')
                    ->orWhere('description', 'like', '%' . $query . '%')
                    ->orWhere('sku', 'like', '%' . $query . '%')
                    ->limit(10)
                    ->get(['id', 'name', 'price', 'stock', 'featured_image']);
        
        return response()->json($products);
    }
    
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.create', compact('categories'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image_url' => 'nullable|url',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'boolean',
        ]);
        
        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');
        
        // Manejar carga de imagen
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . Str::slug($validated['name']) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/products'), $imageName);
            $validated['featured_image'] = '/uploads/products/' . $imageName;
        } elseif (!empty($validated['image_url'])) {
            $validated['featured_image'] = $validated['image_url'];
        }
        
        unset($validated['image_url']);
        unset($validated['image']);
        
        Product::create($validated);
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Producto creado exitosamente');
    }
    
    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }
    
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image_url' => 'nullable|url',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'boolean',
        ]);
        
        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');
        
        // Manejar carga de imagen
        if ($request->hasFile('image')) {
            // Eliminar imagen anterior si existe
            if ($product->featured_image && file_exists(public_path($product->featured_image))) {
                @unlink(public_path($product->featured_image));
            }
            
            $image = $request->file('image');
            $imageName = time() . '_' . Str::slug($validated['name']) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/products'), $imageName);
            $validated['featured_image'] = '/uploads/products/' . $imageName;
        } elseif (!empty($validated['image_url'])) {
            $validated['featured_image'] = $validated['image_url'];
        }
        
        unset($validated['image_url']);
        unset($validated['image']);
        
        $product->update($validated);
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Producto actualizado exitosamente');
    }
    
    public function destroy(Product $product)
    {
        $product->delete();
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Producto eliminado exitosamente');
    }
}
