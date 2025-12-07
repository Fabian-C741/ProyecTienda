@extends('admin.layout')

@section('title', 'Editar Producto')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.products.index') }}" class="text-blue-600 hover:text-blue-800">
        <i class="fas fa-arrow-left mr-2"></i>Volver a productos
    </a>
    <h1 class="text-3xl font-bold text-gray-800 mt-2">Editar Producto: {{ $product->name }}</h1>
</div>

<div class="bg-white rounded-lg shadow-md p-8">
    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nombre -->
            <div class="md:col-span-2">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nombre del Producto *</label>
                <input type="text" name="name" id="name" required value="{{ old('name', $product->name) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Descripción -->
            <div class="md:col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Descripción *</label>
                <textarea name="description" id="description" rows="4" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Categoría -->
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Categoría *</label>
                <select name="category_id" id="category_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('category_id') border-red-500 @enderror">
                    <option value="">Seleccionar categoría</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Precio -->
            <div>
                <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Precio *</label>
                <div class="relative">
                    <span class="absolute left-3 top-2.5 text-gray-500">$</span>
                    <input type="number" name="price" id="price" step="0.01" min="0" required value="{{ old('price', $product->price) }}" class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('price') border-red-500 @enderror">
                </div>
                @error('price')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Stock -->
            <div>
                <label for="stock" class="block text-sm font-medium text-gray-700 mb-2">Stock *</label>
                <input type="number" name="stock" id="stock" min="0" required value="{{ old('stock', $product->stock) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('stock') border-red-500 @enderror">
                @error('stock')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- URL de Imagen -->
            <div>
                <label for="image_url" class="block text-sm font-medium text-gray-700 mb-2">URL de Imagen</label>
                <input type="url" name="image_url" id="image_url" value="{{ old('image_url', $product->featured_image) }}" placeholder="https://ejemplo.com/imagen.jpg" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('image_url') border-red-500 @enderror">
                @error('image_url')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Ingresa una URL de imagen externa</p>
            </div>
            
            <!-- Subir Imagen desde PC -->
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">O Subir Nueva Imagen desde tu PC</label>
                <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('image') border-red-500 @enderror">
                @error('image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">JPG, PNG, GIF o WEBP. Máximo 2MB</p>
                @if($product->featured_image)
                    <div class="mt-2">
                        <p class="text-xs text-gray-600 mb-1">Imagen actual:</p>
                        <img src="{{ $product->featured_image }}" alt="{{ $product->name }}" class="w-32 h-32 object-cover rounded-lg border">
                    </div>
                @endif
            </div>
            
            <!-- Estado Activo -->
            <div class="md:col-span-2">
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <label for="is_active" class="ml-2 block text-sm text-gray-700">
                        Producto activo (visible en la tienda)
                    </label>
                </div>
            </div>
            
            <!-- Estadísticas del producto -->
            <div class="md:col-span-2 bg-gray-50 rounded-lg p-4">
                <h3 class="font-semibold text-gray-700 mb-3">Estadísticas</h3>
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div>
                        <div class="text-2xl font-bold text-blue-600">{{ $product->total_reviews }}</div>
                        <div class="text-xs text-gray-600">Reviews</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-yellow-600 flex items-center justify-center">
                            <i class="fas fa-star text-sm mr-1"></i>{{ number_format($product->average_rating, 1) }}
                        </div>
                        <div class="text-xs text-gray-600">Rating Promedio</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-green-600">{{ $product->stock }}</div>
                        <div class="text-xs text-gray-600">Unidades en Stock</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Botones -->
        <div class="mt-8 flex justify-end space-x-4">
            <a href="{{ route('admin.products.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition shadow-lg">
                <i class="fas fa-save mr-2"></i>Actualizar Producto
            </button>
        </div>
    </form>
</div>
@endsection
