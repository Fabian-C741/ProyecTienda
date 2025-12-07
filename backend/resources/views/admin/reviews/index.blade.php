@extends('admin.layout')

@section('title', 'Reseñas')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Reseñas de Productos</h1>
</div>

<div class="bg-white rounded-lg shadow-md p-4 mb-4">
    <form method="GET" class="flex gap-4">
        <select name="rating" class="px-4 py-2 border rounded-lg">
            <option value="">Todas las calificaciones</option>
            <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>⭐⭐⭐⭐⭐ 5 estrellas</option>
            <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>⭐⭐⭐⭐ 4 estrellas</option>
            <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>⭐⭐⭐ 3 estrellas</option>
            <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>⭐⭐ 2 estrellas</option>
            <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>⭐ 1 estrella</option>
        </select>
        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg">
            Filtrar
        </button>
    </form>
</div>

@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
    {{ session('success') }}
</div>
@endif

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Producto</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usuario</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Calificación</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Comentario</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($reviews as $review)
            <tr>
                <td class="px-6 py-4">{{ $review->id }}</td>
                <td class="px-6 py-4 font-medium">{{ $review->product->name }}</td>
                <td class="px-6 py-4">{{ $review->user->name }}</td>
                <td class="px-6 py-4">
                    <span class="text-yellow-500">{{ str_repeat('⭐', $review->rating) }}</span>
                </td>
                <td class="px-6 py-4 max-w-xs truncate">{{ $review->comment }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $review->created_at->format('d/m/Y') }}</td>
                <td class="px-6 py-4 flex gap-2">
                    <a href="{{ route('admin.reviews.show', $review) }}" class="text-blue-600 hover:text-blue-800">
                        Ver
                    </a>
                    <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('¿Eliminar esta reseña?')">
                            Eliminar
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-4 text-center text-gray-500">No hay reseñas</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="px-6 py-4 bg-gray-50">
        {{ $reviews->links() }}
    </div>
</div>
@endsection
