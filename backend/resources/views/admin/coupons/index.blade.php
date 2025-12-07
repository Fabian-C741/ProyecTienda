@extends('admin.layout')

@section('title', 'Cupones de Descuento')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-ticket-alt mr-2"></i>Cupones de Descuento
        </h1>
        <a href="{{ route('admin.coupons.create') }}" class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition">
            <i class="fas fa-plus mr-2"></i>Nuevo Cupón
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('admin.coupons.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por código..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos los tipos</option>
                    <option value="percentage" {{ request('type') == 'percentage' ? 'selected' : '' }}>Porcentaje</option>
                    <option value="fixed" {{ request('type') == 'fixed' ? 'selected' : '' }}>Fijo</option>
                </select>
            </div>
            <div>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos los estados</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Activo</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                    <i class="fas fa-search mr-2"></i>Filtrar
                </button>
                <a href="{{ route('admin.coupons.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Tabla de Cupones -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Código</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Descuento</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usos</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vencimiento</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($coupons as $coupon)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <span class="font-bold text-blue-600 text-lg">{{ $coupon->code }}</span>
                            @if($coupon->description)
                                <p class="text-xs text-gray-500 mt-1">{{ Str::limit($coupon->description, 40) }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($coupon->type == 'percentage')
                                <span class="px-3 py-1 text-xs rounded-full bg-purple-100 text-purple-800">
                                    <i class="fas fa-percent mr-1"></i>Porcentaje
                                </span>
                            @else
                                <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-dollar-sign mr-1"></i>Fijo
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-semibold">
                            @if($coupon->type == 'percentage')
                                {{ $coupon->value }}%
                            @else
                                ${{ number_format($coupon->value, 2) }}
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm">{{ $coupon->used_count }}</span>
                            @if($coupon->max_uses)
                                <span class="text-gray-500">/ {{ $coupon->max_uses }}</span>
                            @else
                                <span class="text-gray-500">/ ∞</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @if($coupon->expires_at)
                                @if($coupon->expires_at->isPast())
                                    <span class="text-red-600">
                                        <i class="fas fa-times-circle mr-1"></i>Expirado
                                    </span>
                                @else
                                    <span class="text-gray-600">
                                        {{ $coupon->expires_at->format('d/m/Y') }}
                                    </span>
                                @endif
                            @else
                                <span class="text-gray-400">Sin vencimiento</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($coupon->is_active && (!$coupon->expires_at || $coupon->expires_at->isFuture()) && (!$coupon->max_uses || $coupon->used_count < $coupon->max_uses))
                                <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-800">Activo</span>
                            @else
                                <span class="px-3 py-1 text-xs rounded-full bg-red-100 text-red-800">Inactivo</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.coupons.edit', $coupon) }}" class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}" class="inline" onsubmit="return confirm('¿Eliminar este cupón?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-ticket-alt text-4xl mb-3"></i>
                            <p>No hay cupones registrados</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $coupons->links() }}
    </div>
</div>
@endsection
