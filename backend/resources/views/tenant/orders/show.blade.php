@extends('tenant.layout')

@section('title', 'Detalle del Pedido')
@section('page-title', 'Pedido #' . $order->order_number)

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Order Items -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Productos del Pedido</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($item->product && $item->product->image_url)
                                            <img src="{{ $item->product->image_url }}" 
                                                 alt="{{ $item->product_name }}" 
                                                 class="rounded me-3" 
                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                        @endif
                                        <div>
                                            <strong>{{ $item->product_name }}</strong>
                                            @if($item->product)
                                                <br><small class="text-muted">SKU: {{ $item->product->sku }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>${{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td><strong>${{ number_format($item->subtotal, 2) }}</strong></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                <td><strong>${{ number_format($order->subtotal, 2) }}</strong></td>
                            </tr>
                            @if($order->discount > 0)
                            <tr>
                                <td colspan="3" class="text-end">
                                    <strong>Descuento:</strong>
                                    @if($order->coupon_code)
                                        <br><small class="text-muted">Cupón: {{ $order->coupon_code }}</small>
                                    @endif
                                </td>
                                <td class="text-success">-${{ number_format($order->discount, 2) }}</td>
                            </tr>
                            @endif
                            @if($order->tax > 0)
                            <tr>
                                <td colspan="3" class="text-end"><strong>Impuestos:</strong></td>
                                <td>${{ number_format($order->tax, 2) }}</td>
                            </tr>
                            @endif
                            @if($order->shipping_cost > 0)
                            <tr>
                                <td colspan="3" class="text-end"><strong>Envío:</strong></td>
                                <td>${{ number_format($order->shipping_cost, 2) }}</td>
                            </tr>
                            @endif
                            <tr class="table-active">
                                <td colspan="3" class="text-end"><h5 class="mb-0">Total:</h5></td>
                                <td><h5 class="mb-0">${{ number_format($order->total, 2) }}</h5></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Shipping Information -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Información de Envío</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Dirección de Entrega</h6>
                        <p>
                            {{ $order->shipping_address }}<br>
                            {{ $order->shipping_city }}, {{ $order->shipping_state }}<br>
                            CP: {{ $order->shipping_zip }}<br>
                            {{ $order->shipping_country }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6>Datos de Contacto</h6>
                        <p>
                            <strong>Nombre:</strong> {{ $order->customer_name }}<br>
                            <strong>Email:</strong> {{ $order->customer_email }}<br>
                            <strong>Teléfono:</strong> {{ $order->customer_phone }}
                        </p>
                    </div>
                </div>
                @if($order->notes)
                <hr>
                <h6>Notas del Cliente</h6>
                <p class="mb-0">{{ $order->notes }}</p>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Order Status -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Estado del Pedido</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('tenant.orders.updateStatus', $order) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Estado</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pendiente</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Procesando</option>
                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Enviado</option>
                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Entregado</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-circle me-2"></i>Actualizar Estado
                    </button>
                </form>
                
                <hr>
                
                <div class="small">
                    <p class="mb-2">
                        <i class="bi bi-calendar-event me-2"></i>
                        <strong>Creado:</strong> {{ $order->created_at->format('d/m/Y H:i') }}
                    </p>
                    @if($order->shipped_at)
                    <p class="mb-2">
                        <i class="bi bi-truck me-2"></i>
                        <strong>Enviado:</strong> {{ $order->shipped_at->format('d/m/Y H:i') }}
                    </p>
                    @endif
                    @if($order->delivered_at)
                    <p class="mb-2">
                        <i class="bi bi-check-circle me-2"></i>
                        <strong>Entregado:</strong> {{ $order->delivered_at->format('d/m/Y H:i') }}
                    </p>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Payment Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Información de Pago</h5>
            </div>
            <div class="card-body">
                <p class="mb-2">
                    <strong>Método:</strong> 
                    @if($order->payment_method === 'mercadopago')
                        Mercado Pago
                    @elseif($order->payment_method === 'bank_transfer')
                        Transferencia Bancaria
                    @elseif($order->payment_method === 'cash')
                        Efectivo
                    @else
                        {{ ucfirst($order->payment_method) }}
                    @endif
                </p>
                <p class="mb-2">
                    <strong>Estado:</strong>
                    @if($order->payment_status === 'pending')
                        <span class="badge bg-warning">Pendiente</span>
                    @elseif($order->payment_status === 'paid')
                        <span class="badge bg-success">Pagado</span>
                    @elseif($order->payment_status === 'failed')
                        <span class="badge bg-danger">Fallido</span>
                    @endif
                </p>
                @if($order->payment_id)
                <p class="mb-0">
                    <strong>ID de Pago:</strong><br>
                    <small class="text-muted">{{ $order->payment_id }}</small>
                </p>
                @endif
            </div>
        </div>
        
        <!-- Customer Info -->
        @if($order->user)
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Cliente</h5>
            </div>
            <div class="card-body">
                <p class="mb-2">
                    <i class="bi bi-person me-2"></i>
                    <strong>{{ $order->user->name }}</strong>
                </p>
                <p class="mb-2">
                    <i class="bi bi-envelope me-2"></i>
                    {{ $order->user->email }}
                </p>
                <p class="mb-0 small text-muted">
                    Cliente desde: {{ $order->user->created_at->format('d/m/Y') }}
                </p>
            </div>
        </div>
        @endif
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('tenant.orders.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Volver a Pedidos
    </a>
</div>
@endsection
