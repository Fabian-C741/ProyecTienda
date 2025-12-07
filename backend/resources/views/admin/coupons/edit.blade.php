@extends('admin.layout')

@section('title', 'Editar Cupón')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="flex items-center mb-6">
            <a href="{{ route('admin.coupons.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-edit mr-2"></i>Editar Cupón: {{ $coupon->code }}
            </h1>
        </div>

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-md p-8">
            <form method="POST" action="{{ route('admin.coupons.update', $coupon) }}">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Código -->
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 font-semibold mb-2">
                            Código del Cupón <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="code" value="{{ old('code', $coupon->code) }}" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 uppercase"
                               placeholder="Ej: VERANO2024">
                    </div>

                    <!-- Tipo -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">
                            Tipo de Descuento <span class="text-red-500">*</span>
                        </label>
                        <select name="type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="percentage" {{ old('type', $coupon->type) == 'percentage' ? 'selected' : '' }}>Porcentaje (%)</option>
                            <option value="fixed" {{ old('type', $coupon->type) == 'fixed' ? 'selected' : '' }}>Monto Fijo ($)</option>
                        </select>
                    </div>

                    <!-- Valor -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">
                            Valor del Descuento <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="value" step="0.01" min="0" value="{{ old('value', $coupon->value) }}" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Compra Mínima -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">
                            Compra Mínima (Opcional)
                        </label>
                        <input type="number" name="min_purchase" step="0.01" min="0" value="{{ old('min_purchase', $coupon->min_purchase) }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Usos Máximos -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">
                            Usos Máximos (Opcional)
                        </label>
                        <input type="number" name="max_uses" min="1" value="{{ old('max_uses', $coupon->max_uses) }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Usos Actuales (solo lectura) -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">
                            Usos Actuales
                        </label>
                        <input type="text" value="{{ $coupon->used_count }}" readonly 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100">
                    </div>

                    <!-- Fecha de Expiración -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">
                            Fecha de Expiración (Opcional)
                        </label>
                        <input type="datetime-local" name="expires_at" 
                               value="{{ old('expires_at', $coupon->expires_at ? $coupon->expires_at->format('Y-m-d\TH:i') : '') }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Estado -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">
                            Estado
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $coupon->is_active) ? 'checked' : '' }} 
                                   class="w-5 h-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-500">
                            <span class="ml-2 text-gray-700">Cupón activo</span>
                        </label>
                    </div>

                    <!-- Descripción -->
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 font-semibold mb-2">
                            Descripción (Opcional)
                        </label>
                        <textarea name="description" rows="3" 
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('description', $coupon->description) }}</textarea>
                    </div>
                </div>

                <div class="flex justify-end space-x-4 mt-8">
                    <a href="{{ route('admin.coupons.index') }}" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 transition">
                        Cancelar
                    </a>
                    <button type="submit" class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition">
                        <i class="fas fa-save mr-2"></i>Actualizar Cupón
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
