@extends('tenant.layout')

@section('title', 'Pedidos')
@section('page-title', 'Gestión de Pedidos')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-cart-check me-2"></i>Pedidos
        </h5>
    </div>
    <div class="card-body">
        <!-- Filters -->
        <form method="GET" action="{{ route('vendedor.pedidos.index') }}" class="row g-3 mb-4">
            <div class="col-md-4">
                <label for="status" class="form-label">Estado</label>
                <select name="status" id="status" class="form-select">
                    <option value="">Todos los estados</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendiente</option>
                    <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Procesando</option>
                    <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Enviado</option>
                    <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Entregado</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="search" class="form-label">Buscar</label>
                <input type="text" name="search" id="search" class="form-control" 
                       placeholder="Nº pedido, cliente, email..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search me-2"></i>Filtrar
                </button>
            </div>
        </form>
        
        @if($orders->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nº Pedido</th>
                            <th>Cliente</th>
                            <th>Fecha</th>
                            <th>Total</th>
                            <th>Pago</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>
                                <strong>{{ $order->order_number }}</strong>
                            </td>
                            <td>
                                {{ $order->customer_name }}<br>
                                <small class="text-muted">{{ $order->customer_email }}</small>
                            </td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td><strong>${{ number_format($order->total, 2) }}</strong></td>
                            <td>
                                @if($order->payment_status === 'pending')
                                    <span class="badge bg-warning">Pendiente</span>
                                @elseif($order->payment_status === 'paid')
                                    <span class="badge bg-success">Pagado</span>
                                @elseif($order->payment_status === 'failed')
                                    <span class="badge bg-danger">Fallido</span>
                                @endif
                                <br>
                                <small class="text-muted">{{ ucfirst($order->payment_method) }}</small>
                            </td>
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
                            <td>
                                <a href="{{ route('vendedor.pedidos.show', $order) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye me-1"></i>Ver
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $orders->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-cart-x display-1 text-muted"></i>
                <h4 class="mt-3">No se encontraron pedidos</h4>
                <p class="text-muted">
                    @if(request()->hasAny(['status', 'search']))
                        Intenta con otros filtros de búsqueda
                    @else
                        Los pedidos aparecerán aquí cuando los clientes realicen compras
                    @endif
                </p>
                @if(request()->hasAny(['status', 'search']))
                    <a href="{{ route('vendedor.pedidos.index') }}" class="btn btn-outline-secondary mt-2">
                        Limpiar filtros
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
