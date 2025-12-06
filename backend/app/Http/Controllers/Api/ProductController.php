<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'tenant'])
            ->active()
            ->inStock();

        // Filtro por tenant
        if ($request->has('tenant_id')) {
            $query->where('tenant_id', $request->tenant_id);
        }

        // Filtro por categoría
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Búsqueda
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Filtro por rango de precio
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Ordenamiento
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Solo productos destacados
        if ($request->boolean('featured')) {
            $query->featured();
        }

        $perPage = $request->get('per_page', 12);
        $products = $query->paginate($perPage);

        return response()->json($products);
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->with(['category', 'tenant'])
            ->firstOrFail();

        // Incrementar vistas
        $product->incrementViews();

        return response()->json([
            'product' => $product,
        ]);
    }

    public function featured(Request $request)
    {
        $tenantId = $request->get('tenant_id');
        
        $products = Product::active()
            ->featured()
            ->inStock()
            ->when($tenantId, fn($q) => $q->where('tenant_id', $tenantId))
            ->with(['category', 'tenant'])
            ->limit(8)
            ->get();

        return response()->json([
            'products' => $products,
        ]);
    }

    public function relatedProducts($productId, Request $request)
    {
        $product = Product::findOrFail($productId);

        $related = Product::active()
            ->inStock()
            ->where('id', '!=', $product->id)
            ->where('tenant_id', $product->tenant_id)
            ->where(function($q) use ($product) {
                $q->where('category_id', $product->category_id);
            })
            ->limit(4)
            ->get();

        return response()->json([
            'products' => $related,
        ]);
    }
}
