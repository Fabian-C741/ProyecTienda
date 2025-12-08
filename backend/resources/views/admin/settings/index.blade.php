@extends('admin.layout')

@section('title', 'Configuración')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Configuración del Sistema</h1>
</div>

@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
    {{ session('error') }}
</div>
@endif

@if($errors->any())
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
    <ul class="list-disc list-inside">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Cambiar Contraseña (Solo Super Admin) -->
    @if(Auth::check() && Auth::user()->role === 'admin')
    <div class="bg-white rounded-lg shadow-md p-6 md:col-span-2">
        <h3 class="text-xl font-semibold mb-4 flex items-center">
            <i class="fas fa-key text-blue-600 mr-2"></i>
            Cambiar Contraseña del Super Admin
        </h3>
        <form action="{{ route('admin.settings.update-password') }}" method="POST" class="max-w-2xl">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Contraseña Actual</label>
                    <input type="password" name="current_password" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="••••••••">
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Nueva Contraseña</label>
                    <input type="password" name="new_password" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="••••••••">
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Confirmar Nueva</label>
                    <input type="password" name="new_password_confirmation" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="••••••••">
                </div>
            </div>
            <button type="submit" class="mt-4 bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg">
                <i class="fas fa-save mr-2"></i>Actualizar Contraseña
            </button>
        </form>
    </div>
    @endif

    <!-- Caché -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-semibold mb-4">Gestión de Caché</h3>
        <p class="text-gray-600 mb-4">Limpiar la caché puede resolver problemas de rendimiento y actualizar cambios en configuración.</p>
        <form action="{{ route('admin.settings.clear-cache') }}" method="POST">
            @csrf
            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg">
                Limpiar Caché
            </button>
        </form>
    </div>

    <!-- Información del Sistema -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-semibold mb-4">Información del Sistema</h3>
        <div class="space-y-2 text-sm">
            <p><strong>Laravel:</strong> {{ app()->version() }}</p>
            <p><strong>PHP:</strong> {{ PHP_VERSION }}</p>
            <p><strong>Entorno:</strong> {{ app()->environment() }}</p>
            <p><strong>Debug:</strong> {{ config('app.debug') ? 'Activado' : 'Desactivado' }}</p>
        </div>
    </div>

    <!-- Email -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-semibold mb-4">Configuración de Email</h3>
        <div class="space-y-2 text-sm">
            <p><strong>Driver:</strong> {{ config('mail.default') }}</p>
            <p><strong>Host:</strong> {{ config('mail.mailers.smtp.host') }}</p>
            <p><strong>Puerto:</strong> {{ config('mail.mailers.smtp.port') }}</p>
        </div>
    </div>

    <!-- Base de Datos -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-semibold mb-4">Base de Datos</h3>
        <div class="space-y-2 text-sm">
            <p><strong>Conexión:</strong> {{ config('database.default') }}</p>
            <p><strong>Base de Datos:</strong> {{ config('database.connections.mysql.database') }}</p>
            <p><strong>Host:</strong> {{ config('database.connections.mysql.host') }}</p>
        </div>
    </div>
</div>
@endsection
