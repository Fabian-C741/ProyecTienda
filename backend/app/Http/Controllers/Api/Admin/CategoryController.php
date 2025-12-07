<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Listar todas las categorías
     */
    public function index(Request $request)
    {
        $query = Category::withCount('products');

        // Filtro por tenant
        if (!$request->user()->hasRole('super_admin')) {
            $query->where('tenant_id', $request->user()->tenant_id);
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $categories = $query->orderBy('name')->get();

        return response()->json($categories);
    }

    /**
     * Mostrar una categoría específica
     */
    public function show($id)
    {
        $category = Category::withCount('products')->findOrFail($id);

        return response()->json($category);
    }

    /**
     * Crear una nueva categoría
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|url',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $category = Category::create([
            'tenant_id' => $request->user()->tenant_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'image' => $request->image,
            'is_active' => $request->is_active ?? true,
        ]);

        return response()->json([
            'message' => 'Categoría creada exitosamente',
            'category' => $category,
        ], 201);
    }

    /**
     * Actualizar una categoría
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        if (!$request->user()->hasRole('super_admin') && $category->tenant_id != $request->user()->tenant_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|url',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only(['name', 'description', 'image', 'is_active']);
        
        if (isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $category->update($data);

        return response()->json([
            'message' => 'Categoría actualizada exitosamente',
            'category' => $category,
        ]);
    }

    /**
     * Eliminar una categoría
     */
    public function destroy(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        if (!$request->user()->hasRole('super_admin') && $category->tenant_id != $request->user()->tenant_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        // Verificar que no tenga productos
        if ($category->products()->count() > 0) {
            return response()->json([
                'message' => 'No se puede eliminar una categoría que tiene productos asignados'
            ], 422);
        }

        $category->delete();

        return response()->json([
            'message' => 'Categoría eliminada exitosamente'
        ]);
    }
}
