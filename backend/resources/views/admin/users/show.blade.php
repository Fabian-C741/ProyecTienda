@extends('admin.layout')

@section('title', 'Detalle del Usuario')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Detalle del Usuario</h1>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Información Personal</h2>
        <div class="space-y-2">
            <p><strong>Nombre:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Registro:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Estadísticas</h2>
        <div class="space-y-2">
            <p><strong>Total Órdenes:</strong> {{ $user->orders->count() }}</p>
            <p><strong>Total Gastado:</strong> ${{ number_format($user->orders->sum('total'), 2) }}</p>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-xl font-semibold mb-4">Últimas Órdenes</h2>
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($user->orders as $order)
            <tr>
                <td class="px-6 py-4">#{{ $order->id }}</td>
                <td class="px-6 py-4">{{ $order->created_at->format('d/m/Y') }}</td>
                <td class="px-6 py-4">${{ number_format($order->total, 2) }}</td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 rounded text-sm bg-{{ $order->status === 'completed' ? 'green' : 'yellow' }}-100 text-{{ $order->status === 'completed' ? 'green' : 'yellow' }}-800">
                        {{ ucfirst($order->status) }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-6 py-4 text-center text-gray-500">No hay órdenes</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    <a href="{{ route('admin.users.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg inline-block">
        Volver
    </a>
</div>
@endsection
