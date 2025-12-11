@extends('tenant.layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row g-4 mb-4">
    <!-- Total Products -->
    <div class="col-md-3">
        <div class="stats-card primary">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-muted mb-1">Total Productos</p>
                    <h3 class="mb-0">{{ $stats['total_products'] }}</h3>
                </div>
                <div class="icon">
                    <i class="bi bi-box-seam"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Total Orders -->
    <div class="col-md-3">
        <div class="stats-card success">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-muted mb-1">Total Pedidos</p>
                    <h3 class="mb-0">{{ $stats['total_orders'] }}</h3>
                </div>
                <div class="icon">
                    <i class="bi bi-cart-check"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Pending Orders -->
    <div class="col-md-3">
        <div class="stats-card warning">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-muted mb-1">Pedidos Pendientes</p>
                    <h3 class="mb-0">{{ $stats['pending_orders'] }}</h3>
                </div>
                <div class="icon">
                    <i class="bi bi-clock-history"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Total Revenue -->
    <div class="col-md-3">
        <div class="stats-card info">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-muted mb-1">Ingresos Totales</p>
                    <h3 class="mb-0">${{ number_format($stats['total_revenue'], 2) }}</h3>
                    <small class="text-muted">Este mes: ${{ number_format($stats['this_month_revenue'], 2) }}</small>
                </div>
                <div class="icon">
                    <i class="bi bi-currency-dollar"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Orders -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Pedidos Recientes</h5>
                <a href="{{ route('vendedor.pedidos.index') }}" class="btn btn-sm btn-outline-primary">Ver Todos</a>
            </div>
            <div class="card-body p-0">
                @if($recentOrders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Nº Pedido</th>
                                    <th>Cliente</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                <tr>
                                    <td><strong>{{ $order->order_number }}</strong></td>
                                    <td>{{ $order->customer_name }}</td>
                                    <td>${{ number_format($order->total, 2) }}</td>
                                    <td>
                                        @if($order->status === 'pending')
                                            <span class="badge bg-warning">Pendiente</span>
                                        @elseif($order->status === 'processing')
                                            <span class="badge bg-info">Procesando</span>
                                        @elseif($order->status === 'shipped')
                                            <span class="badge bg-primary">Enviado</span>
                                        @elseif($order->status === 'delivered')
                                            <span class="badge bg-success">Entregado</span>
                                        @elseif($order->status === 'cancelled')
                                            <span class="badge bg-danger">Cancelado</span>
                                        @endif
                                    </td>
                                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('vendedor.pedidos.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-cart-x display-4 text-muted"></i>
                        <p class="text-muted mt-3">No hay pedidos recientes</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Top Products -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Productos Más Vendidos</h5>
            </div>
            <div class="card-body">
                @if($topProducts->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($topProducts as $product)
                        <div class="list-group-item px-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $product->name }}</h6>
                                    <small class="text-muted">{{ $product->order_items_count }} ventas</small>
                                </div>
                                <span class="badge bg-primary rounded-pill">{{ $product->order_items_count }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center my-3">No hay datos de ventas</p>
                @endif
            </div>
        </div>
        
        <!-- Low Stock Alert -->
        <div class="card">
            <div class="card-header bg-warning bg-opacity-10">
                <h5 class="mb-0 text-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>Stock Bajo
                </h5>
            </div>
            <div class="card-body">
                @if($lowStockProducts->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($lowStockProducts as $product)
                        <div class="list-group-item px-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $product->name }}</h6>
                                    <small class="text-danger">Stock: {{ $product->stock }} unidades</small>
                                </div>
                                <a href="{{ route('vendedor.productos.edit', $product) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center my-3">
                        <i class="bi bi-check-circle text-success"></i>
                        Stock en buen estado
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
