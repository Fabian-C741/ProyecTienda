<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Listar todos los productos (admin)
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'tenant']);

        // Filtros
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
            });
        }

        // Filtro por tenant (solo super_admin puede ver todos)
        if (!$request->user()->hasRole('super_admin')) {
            $query->where('tenant_id', $request->user()->tenant_id);
        }

        $products = $query->orderBy('created_at', 'desc')
                         ->paginate($request->per_page ?? 20);

        return response()->json($products);
    }

    /**
     * Mostrar un producto específico
     */
    public function show($id)
    {
        $product = Product::with(['category', 'tenant'])->findOrFail($id);

        return response()->json($product);
    }

    /**
     * Crear un nuevo producto
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'nullable|string|unique:products,sku',
            'images' => 'nullable|array',
            'images.*' => 'url',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product = Product::create([
            'tenant_id' => $request->user()->tenant_id,
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'sku' => $request->sku ?? 'PRD-' . strtoupper(Str::random(8)),
            'images' => $request->images ?? [],
            'is_active' => $request->is_active ?? true,
        ]);

        return response()->json([
            'message' => 'Producto creado exitosamente',
            'product' => $product->load('category'),
        ], 201);
    }

    /**
     * Actualizar un producto
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // Verificar que el producto pertenece al tenant del usuario
        if (!$request->user()->hasRole('super_admin') && $product->tenant_id != $request->user()->tenant_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'category_id' => 'sometimes|exists:categories,id',
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
            'sku' => 'sometimes|string|unique:products,sku,' . $id,
            'images' => 'nullable|array',
            'images.*' => 'url',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only(['category_id', 'name', 'description', 'price', 'stock', 'sku', 'images', 'is_active']);
        
        if (isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $product->update($data);

        return response()->json([
            'message' => 'Producto actualizado exitosamente',
            'product' => $product->load('category'),
        ]);
    }

    /**
     * Eliminar un producto
     */
    public function destroy(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // Verificar que el producto pertenece al tenant del usuario
        if (!$request->user()->hasRole('super_admin') && $product->tenant_id != $request->user()->tenant_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $product->delete();

        return response()->json([
            'message' => 'Producto eliminado exitosamente'
        ]);
    }

    /**
     * Publicar un producto
     */
    public function publish(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if (!$request->user()->hasRole('super_admin') && $product->tenant_id != $request->user()->tenant_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $product->update(['is_active' => true]);

        return response()->json([
            'message' => 'Producto publicado exitosamente',
            'product' => $product,
        ]);
    }

    /**
     * Despublicar un producto
     */
    public function unpublish(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if (!$request->user()->hasRole('super_admin') && $product->tenant_id != $request->user()->tenant_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $product->update(['is_active' => false]);

        return response()->json([
            'message' => 'Producto despublicado exitosamente',
            'product' => $product,
        ]);
    }
}
