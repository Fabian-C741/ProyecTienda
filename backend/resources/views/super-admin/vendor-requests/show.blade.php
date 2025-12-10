<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de Solicitud - Super Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('dashboard.index') }}" class="text-xl font-bold text-purple-600">
                        <i class="fas fa-shield-alt mr-2"></i>Super Admin
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700">{{ auth()->user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-800">
                            <i class="fas fa-sign-out-alt"></i> Salir
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <div class="mb-6">
            <a href="{{ route('dashboard.index') }}" class="text-purple-600 hover:text-purple-800">
                <i class="fas fa-home"></i> Dashboard
            </a>
            <span class="text-gray-400 mx-2">/</span>
            <a href="{{ route('super-admin.vendor-requests') }}" class="text-purple-600 hover:text-purple-800">
                Solicitudes
            </a>
            <span class="text-gray-400 mx-2">/</span>
            <span class="text-gray-600">Detalles</span>
        </div>

        <!-- Mensajes -->
        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            </div>
        @endif

        <!-- Estado de la Solicitud -->
        <div class="bg-white rounded-lg shadow mb-6 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $vendorRequest->store_name }}</h1>
                    <p class="text-gray-600">Solicitud ID: #{{ $vendorRequest->id }}</p>
                </div>
                <div>
                    @if($vendorRequest->status == 'pending')
                        <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            <i class="fas fa-clock mr-2"></i> Pendiente de Revisión
                        </span>
                    @elseif($vendorRequest->status == 'approved')
                        <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-2"></i> Aprobada
                        </span>
                    @else
                        <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                            <i class="fas fa-times-circle mr-2"></i> Rechazada
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Información de la Tienda -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Datos Básicos -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">
                        <i class="fas fa-store text-purple-600 mr-2"></i>Información de la Tienda
                    </h2>
                    
                    <div class="space-y-4">
                        <div class="border-b pb-3">
                            <p class="text-sm text-gray-500">Nombre de la Tienda</p>
                            <p class="font-semibold text-lg">{{ $vendorRequest->store_name }}</p>
                        </div>
                        
                        <div class="border-b pb-3">
                            <p class="text-sm text-gray-500">URL (Subdominio)</p>
                            <p class="font-semibold text-blue-600">
                                <i class="fas fa-link mr-1"></i>
                                https://{{ $vendorRequest->slug }}.ingreso-tienda.kcrsf.com
                            </p>
                        </div>
                        
                        <div class="border-b pb-3">
                            <p class="text-sm text-gray-500">Categoría</p>
                            <p class="font-semibold">{{ $vendorRequest->category }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500">Descripción del Negocio</p>
                            <p class="mt-2 text-gray-700">{{ $vendorRequest->description }}</p>
                        </div>
                    </div>
                </div>

                <!-- Datos del Propietario -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">
                        <i class="fas fa-user text-purple-600 mr-2"></i>Información del Propietario
                    </h2>
                    
                    <div class="space-y-4">
                        <div class="border-b pb-3">
                            <p class="text-sm text-gray-500">Nombre Completo</p>
                            <p class="font-semibold">{{ $vendorRequest->owner_name }}</p>
                        </div>
                        
                        <div class="border-b pb-3">
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="font-semibold">
                                <a href="mailto:{{ $vendorRequest->email }}" class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-envelope mr-1"></i>{{ $vendorRequest->email }}
                                </a>
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500">Teléfono</p>
                            <p class="font-semibold">
                                <a href="tel:{{ $vendorRequest->phone }}" class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-phone mr-1"></i>{{ $vendorRequest->phone }}
                                </a>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Notas del Admin (si existen) -->
                @if($vendorRequest->admin_notes)
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                        <h3 class="font-bold text-blue-900 mb-2">
                            <i class="fas fa-sticky-note mr-2"></i>Notas del Administrador
                        </h3>
                        <p class="text-blue-800">{{ $vendorRequest->admin_notes }}</p>
                        @if($vendorRequest->approver)
                            <p class="text-sm text-blue-600 mt-2">
                                Por: {{ $vendorRequest->approver->name }} - {{ $vendorRequest->approved_at->format('d/m/Y H:i') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Panel de Acciones -->
            <div class="lg:col-span-1">
                <!-- Info de Fecha -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h3 class="font-bold text-gray-900 mb-4">
                        <i class="fas fa-calendar text-purple-600 mr-2"></i>Información Temporal
                    </h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-gray-500">Fecha de Solicitud</p>
                            <p class="font-semibold">{{ $vendorRequest->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        @if($vendorRequest->approved_at)
                            <div>
                                <p class="text-gray-500">Fecha de Procesamiento</p>
                                <p class="font-semibold">{{ $vendorRequest->approved_at->format('d/m/Y H:i') }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Acciones (solo si está pendiente) -->
                @if($vendorRequest->status == 'pending')
                    <!-- Aprobar -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-4">
                        <h3 class="font-bold text-green-900 mb-4">
                            <i class="fas fa-check-circle mr-2"></i>Aprobar Solicitud
                        </h3>
                        <form action="{{ route('super-admin.vendor-requests.approve', $vendorRequest->id) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Tasa de Comisión (%)
                                </label>
                                <input type="number" 
                                       name="commission_rate" 
                                       step="0.01" 
                                       min="0" 
                                       max="100"
                                       value="10"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md"
                                       required>
                                <p class="text-xs text-gray-500 mt-1">Comisión por cada venta</p>
                            </div>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Notas (Opcional)
                                </label>
                                <textarea name="admin_notes" 
                                          rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md"
                                          placeholder="Notas internas..."></textarea>
                            </div>
                            
                            <button type="submit" 
                                    class="w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700"
                                    onclick="return confirm('¿Confirmas aprobar esta solicitud? Se creará la tienda y el usuario administrador.')">
                                <i class="fas fa-check mr-2"></i>Aprobar y Crear Tienda
                            </button>
                        </form>
                    </div>

                    <!-- Rechazar -->
                    <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                        <h3 class="font-bold text-red-900 mb-4">
                            <i class="fas fa-times-circle mr-2"></i>Rechazar Solicitud
                        </h3>
                        <form action="{{ route('super-admin.vendor-requests.reject', $vendorRequest->id) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Motivo del Rechazo *
                                </label>
                                <textarea name="admin_notes" 
                                          rows="4"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md"
                                          placeholder="Explica por qué se rechaza..."
                                          required></textarea>
                            </div>
                            
                            <button type="submit" 
                                    class="w-full px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700"
                                    onclick="return confirm('¿Confirmas rechazar esta solicitud?')">
                                <i class="fas fa-times mr-2"></i>Rechazar Solicitud
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        <!-- Botón Volver -->
        <div class="mt-6">
            <a href="{{ route('super-admin.vendor-requests') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                <i class="fas fa-arrow-left mr-2"></i>Volver a Solicitudes
            </a>
        </div>
    </div>
</body>
</html>
