<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Pedido</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .order-info {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .item {
            border-bottom: 1px solid #eee;
            padding: 15px 0;
        }
        .item:last-child {
            border-bottom: none;
        }
        .total {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
            text-align: right;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #999;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>¡Gracias por tu pedido!</h1>
        <p>Pedido #{{ $orderNumber }}</p>
    </div>
    
    <div class="content">
        <p>Tu pedido ha sido confirmado y está siendo procesado.</p>
        
        <div class="order-info">
            <h2>Resumen del Pedido</h2>
            
            @foreach($items as $item)
            <div class="item">
                <strong>{{ $item->product->name }}</strong><br>
                Cantidad: {{ $item->quantity }} × ${{ number_format($item->price, 2) }}<br>
                Subtotal: ${{ number_format($item->quantity * $item->price, 2) }}
            </div>
            @endforeach
            
            <div class="total">
                Total: ${{ number_format($total, 2) }}
            </div>
        </div>
        
        @if($shippingAddress)
        <div class="order-info">
            <h3>Dirección de Envío</h3>
            <p>
                {{ $shippingAddress['street'] ?? '' }}<br>
                {{ $shippingAddress['city'] ?? '' }}, {{ $shippingAddress['state'] ?? '' }}<br>
                {{ $shippingAddress['postal_code'] ?? '' }}<br>
                {{ $shippingAddress['country'] ?? '' }}
            </p>
        </div>
        @endif
        
        <p>Recibirás un correo adicional cuando tu pedido sea enviado.</p>
    </div>
    
    <div class="footer">
        <p>© {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.</p>
    </div>
</body>
</html>
