@extends('admin.layout')

@section('title', 'Usuarios')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Usuarios</h1>
</div>

<div class="bg-white rounded-lg shadow-md p-4 mb-4">
    <form method="GET" class="flex gap-4">
        <input type="text" name="search" placeholder="Buscar por nombre o email..." value="{{ request('search') }}" class="flex-1 px-4 py-2 border rounded-lg">
        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg">
            Buscar
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
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Órdenes</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Registro</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($users as $user)
            <tr>
                <td class="px-6 py-4">{{ $user->id }}</td>
                <td class="px-6 py-4 font-medium">{{ $user->name }}</td>
                <td class="px-6 py-4">{{ $user->email }}</td>
                <td class="px-6 py-4">
                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">{{ $user->orders_count }}</span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $user->created_at->format('d/m/Y') }}</td>
                <td class="px-6 py-4 flex gap-2">
                    <a href="{{ route('admin.users.show', $user) }}" class="text-blue-600 hover:text-blue-800">
                        Ver
                    </a>
                    <a href="{{ route('admin.users.edit', $user) }}" class="text-green-600 hover:text-green-800">
                        Editar
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-4 text-center text-gray-500">No hay usuarios registrados</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="px-6 py-4 bg-gray-50">
        {{ $users->links() }}
    </div>
</div>
@endsection
