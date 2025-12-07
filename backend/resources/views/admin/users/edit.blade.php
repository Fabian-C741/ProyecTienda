@extends('admin.layout')

@section('title', 'Editar Usuario')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Editar Usuario</h1>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2">Nombre</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
            @error('name')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
            @error('email')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2">Nueva Contraseña (dejar vacío para no cambiar)</label>
            <input type="password" name="password" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            @error('password')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-4">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg">
                Actualizar Usuario
            </button>
            <a href="{{ route('admin.users.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
