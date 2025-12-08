@extends('admin.layout')

@section('title', 'Crear Usuario')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Crear Nuevo Usuario</h1>
</div>

@if($errors->any())
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
    <ul class="list-disc list-inside">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="bg-white rounded-lg shadow-md p-6">
    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Información Básica -->
            <div class="md:col-span-2">
                <h3 class="text-lg font-semibold mb-4 text-gray-700 border-b pb-2">
                    <i class="fas fa-user-circle mr-2"></i>Información Básica
                </h3>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Nombre Completo *</label>
                <input type="text" name="name" value="{{ old('name') }}" required 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                       placeholder="Juan Pérez">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Email *</label>
                <input type="email" name="email" value="{{ old('email') }}" required 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                       placeholder="usuario@email.com">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Contraseña *</label>
                <input type="password" name="password" required 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                       placeholder="Mínimo 6 caracteres">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Rol *</label>
                <select name="role" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Cliente</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrador</option>
                </select>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Teléfono</label>
                <input type="text" name="phone" value="{{ old('phone') }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                       placeholder="+1 234 567 8900">
            </div>

            <!-- Dirección -->
            <div class="md:col-span-2 mt-4">
                <h3 class="text-lg font-semibold mb-4 text-gray-700 border-b pb-2">
                    <i class="fas fa-map-marker-alt mr-2"></i>Dirección (Opcional)
                </h3>
            </div>

            <div class="md:col-span-2">
                <label class="block text-gray-700 font-semibold mb-2">Dirección</label>
                <input type="text" name="address" value="{{ old('address') }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                       placeholder="Calle Principal 123">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Ciudad</label>
                <input type="text" name="city" value="{{ old('city') }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                       placeholder="Ciudad">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Estado/Provincia</label>
                <input type="text" name="state" value="{{ old('state') }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                       placeholder="Estado">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">País</label>
                <input type="text" name="country" value="{{ old('country') }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                       placeholder="País">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Código Postal</label>
                <input type="text" name="postal_code" value="{{ old('postal_code') }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                       placeholder="12345">
            </div>
        </div>

        <div class="flex gap-4 mt-6 pt-4 border-t">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg">
                <i class="fas fa-save mr-2"></i>Crear Usuario
            </button>
            <a href="{{ route('admin.users.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                <i class="fas fa-times mr-2"></i>Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
