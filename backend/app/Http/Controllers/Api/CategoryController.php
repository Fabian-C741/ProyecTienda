<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::with(['children', 'parent'])
            ->active();

        if ($request->has('tenant_id')) {
            $query->where('tenant_id', $request->tenant_id);
        }

        if ($request->boolean('root_only')) {
            $query->root();
        }

        $categories = $query->orderBy('order')->get();

        return response()->json([
            'categories' => $categories,
        ]);
    }

    public function show($slug)
    {
        $category = Category::where('slug', $slug)
            ->with(['children', 'parent'])
            ->firstOrFail();

        return response()->json([
            'category' => $category,
        ]);
    }

    public function products($slug, Request $request)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $query = $category->products()
            ->active()
            ->inStock()
            ->with(['category', 'tenant']);

        // Búsqueda
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Ordenamiento
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $perPage = $request->get('per_page', 12);
        $products = $query->paginate($perPage);

        return response()->json($products);
    }
}
