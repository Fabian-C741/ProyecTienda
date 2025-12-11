@extends('tenant.layout')

@section('title', 'Editar Producto')
@section('page-title', 'Editar Producto')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Editar: {{ $product->name }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('vendedor.productos.update', $product) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre del Producto <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $product->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Descripción</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="category_id" class="form-label">Categoría <span class="text-danger">*</span></label>
                            <select class="form-select @error('category_id') is-invalid @enderror" 
                                    id="category_id" name="category_id" required>
                                <option value="">Seleccionar categoría</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="sku" class="form-label">SKU</label>
                            <input type="text" class="form-control @error('sku') is-invalid @enderror" 
                                   id="sku" name="sku" value="{{ old('sku', $product->sku) }}">
                            @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="price" class="form-label">Precio <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                       id="price" name="price" value="{{ old('price', $product->price) }}" 
                                       step="0.01" min="0" required>
                            </div>
                            @error('price')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="stock" class="form-label">Stock <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('stock') is-invalid @enderror" 
                                   id="stock" name="stock" value="{{ old('stock', $product->stock) }}" 
                                   min="0" required>
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="low_stock_threshold" class="form-label">Alerta de Stock Bajo</label>
                            <input type="number" class="form-control @error('low_stock_threshold') is-invalid @enderror" 
                                   id="low_stock_threshold" name="low_stock_threshold" 
                                   value="{{ old('low_stock_threshold', $product->low_stock_threshold) }}" min="0">
                            @error('low_stock_threshold')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="image" class="form-label">Imagen del Producto</label>
                        @if($product->image_url)
                            <div class="mb-2">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                                     class="img-thumbnail" style="max-width: 200px;">
                            </div>
                        @endif
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               id="image" name="image" accept="image/*">
                        <small class="text-muted">Tamaño máximo: 2MB. Formatos: JPG, PNG, WEBP. Deja vacío para mantener la imagen actual.</small>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="is_active" 
                               name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Producto activo (visible en la tienda)
                        </label>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Guardar Cambios
                        </button>
                        <a href="{{ route('vendedor.productos.index') }}" class="btn btn-outline-secondary">
                            Cancelar
                        </a>
                        <form action="{{ route('vendedor.productos.destroy', $product) }}" 
                              method="POST" class="ms-auto"
                              onsubmit="return confirm('¿Estás seguro de eliminar este producto? Esta acción no se puede deshacer.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="bi bi-trash me-2"></i>Eliminar Producto
                            </button>
                        </form>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Estadísticas del Producto</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted">Total de Ventas</small>
                    <h4>{{ $product->orderItems->count() }}</h4>
                </div>
                <div class="mb-3">
                    <small class="text-muted">Ingresos Generados</small>
                    <h4>${{ number_format($product->orderItems->sum('subtotal'), 2) }}</h4>
                </div>
                <div class="mb-3">
                    <small class="text-muted">Creado el</small>
                    <p class="mb-0">{{ $product->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <small class="text-muted">Última actualización</small>
                    <p class="mb-0">{{ $product->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">SEO</h5>
            </div>
            <div class="card-body">
                <small class="text-muted">URL del Producto</small>
                <p class="mb-0 small">
                    <a href="{{ route('shop.product', $product->slug) }}" target="_blank">
                        /producto/{{ $product->slug }}
                        <i class="bi bi-box-arrow-up-right"></i>
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
