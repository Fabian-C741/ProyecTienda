<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>
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
            padding: 40px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 40px;
            border-radius: 0 0 10px 10px;
        }
        .button {
            display: inline-block;
            padding: 15px 30px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .features {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .feature {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .feature:last-child {
            border-bottom: none;
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
        <h1>¡Bienvenido {{ $userName }}!</h1>
        <p>Estamos encantados de tenerte con nosotros</p>
    </div>
    
    <div class="content">
        <p>Gracias por registrarte en {{ config('app.name') }}. Tu cuenta ha sido creada exitosamente.</p>
        
        <div class="features">
            <h2>¿Qué puedes hacer ahora?</h2>
            
            <div class="feature">
                ✅ Explorar nuestro catálogo de productos
            </div>
            <div class="feature">
                ✅ Agregar productos a tu carrito
            </div>
            <div class="feature">
                ✅ Realizar compras seguras
            </div>
            <div class="feature">
                ✅ Seguir el estado de tus pedidos
            </div>
            <div class="feature">
                ✅ Dejar reseñas de productos
            </div>
        </div>
        
        <center>
            <a href="{{ config('app.url') }}" class="button">Comenzar a Comprar</a>
        </center>
        
        <p>Si tienes alguna pregunta, no dudes en contactarnos.</p>
    </div>
    
    <div class="footer">
        <p>© {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.</p>
    </div>
</body>
</html>
