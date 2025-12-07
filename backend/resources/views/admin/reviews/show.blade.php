@extends('admin.layout')

@section('title', 'Detalle de Reseña')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Detalle de Reseña</h1>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <div class="mb-6">
        <h2 class="text-xl font-semibold mb-2">{{ $review->product->name }}</h2>
        <div class="flex items-center gap-4 text-sm text-gray-600">
            <span>Por: <strong>{{ $review->user->name }}</strong></span>
            <span>•</span>
            <span>{{ $review->created_at->format('d/m/Y H:i') }}</span>
        </div>
    </div>

    <div class="mb-6">
        <div class="text-2xl text-yellow-500 mb-2">
            {{ str_repeat('⭐', $review->rating) }}
        </div>
        <span class="text-gray-600">{{ $review->rating }}/5</span>
    </div>

    <div class="mb-6">
        <h3 class="font-semibold mb-2">Comentario:</h3>
        <p class="text-gray-700 bg-gray-50 p-4 rounded-lg">{{ $review->comment }}</p>
    </div>

    <div class="flex gap-4">
        <a href="{{ route('admin.reviews.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
            Volver
        </a>
        <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg" onclick="return confirm('¿Eliminar esta reseña?')">
                Eliminar Reseña
            </button>
        </form>
    </div>
</div>
@endsection
