@extends('admin.layout')

@section('title', 'Pasarelas de Pago')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Pasarelas de Pago</h1>
</div>

@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
    {{ session('success') }}
</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    @forelse($gateways as $gateway)
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h3 class="text-xl font-semibold">{{ ucfirst($gateway->name) }}</h3>
                <p class="text-sm text-gray-500 mt-1">{{ $gateway->description }}</p>
            </div>
            <span class="px-3 py-1 rounded-full text-sm {{ $gateway->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                {{ $gateway->is_active ? 'Activa' : 'Inactiva' }}
            </span>
        </div>
        
        <div class="mt-4">
            <a href="{{ route('admin.payment-gateways.edit', $gateway) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg inline-block">
                Configurar
            </a>
        </div>
    </div>
    @empty
    <div class="col-span-2 bg-white rounded-lg shadow-md p-6 text-center text-gray-500">
        No hay pasarelas de pago configuradas
    </div>
    @endforelse
</div>
@endsection
