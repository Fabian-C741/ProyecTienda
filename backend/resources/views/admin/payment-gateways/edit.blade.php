@extends('admin.layout')

@section('title', 'Configurar Pasarela de Pago')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Configurar {{ ucfirst($paymentGateway->name) }}</h1>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <form action="{{ route('admin.payment-gateways.update', $paymentGateway) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label class="flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1" {{ $paymentGateway->is_active ? 'checked' : '' }} class="w-5 h-5 text-blue-600">
                <span class="font-medium">Activar esta pasarela de pago</span>
            </label>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2">API Key</label>
            <input type="text" name="api_key" value="{{ old('api_key', $paymentGateway->credentials['api_key'] ?? '') }}" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            <p class="text-sm text-gray-500 mt-1">Clave pública de la API</p>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2">API Secret</label>
            <input type="password" name="api_secret" value="{{ old('api_secret', $paymentGateway->credentials['api_secret'] ?? '') }}" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            <p class="text-sm text-gray-500 mt-1">Clave secreta de la API (se almacena encriptada)</p>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg">
                Guardar Configuración
            </button>
            <a href="{{ route('admin.payment-gateways.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
