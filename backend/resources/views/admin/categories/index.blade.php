@extends('admin.layout')

@section('title', 'Categorías')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-3xl font-bold text-gray-800">Categorías</h1>
    <a href="{{ route('admin.categories.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nueva Categoría
    </a>
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

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Slug</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Productos</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($categories as $category)
            <tr>
                <td class="px-6 py-4">{{ $category->id }}</td>
                <td class="px-6 py-4 font-medium">{{ $category->name }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $category->slug }}</td>
                <td class="px-6 py-4">
                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">{{ $category->products_count }}</span>
                </td>
                <td class="px-6 py-4 flex gap-2">
                    <a href="{{ route('admin.categories.edit', $category) }}" class="text-blue-600 hover:text-blue-800">
                        Editar
                    </a>
                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('¿Eliminar esta categoría?')">
                            Eliminar
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-4 text-center text-gray-500">No hay categorías registradas</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="px-6 py-4 bg-gray-50">
        {{ $categories->links() }}
    </div>
</div>
@endsection
