#!/bin/bash

echo "=== DIAGNÓSTICO DE RUTAS VENDEDOR ==="
echo "Fecha: $(date)"
echo "======================================="

echo ""
echo "1. Verificando ruta /vendedor/productos"
curl -s -w "HTTP Status: %{http_code}\n" "https://ingreso-tienda.kcrsf.com/vendedor/productos" -H "Cookie: laravel_session=..." | head -20
echo ""

echo "2. Verificando ruta /vendedor/pedidos" 
curl -s -w "HTTP Status: %{http_code}\n" "https://ingreso-tienda.kcrsf.com/vendedor/pedidos" -H "Cookie: laravel_session=..." | head -20
echo ""

echo "3. Verificando ruta /vendedor/configuracion"
curl -s -w "HTTP Status: %{http_code}\n" "https://ingreso-tienda.kcrsf.com/vendedor/configuracion" -H "Cookie: laravel_session=..." | head -20
echo ""

echo "4. Verificando rutas definidas en web.php"
cd /home/u464516792/domains/ingreso-tienda.kcrsf.com/public_html/backend
php artisan route:list | grep vendedor
echo ""

echo "5. Últimos errores en logs"
tail -n 50 storage/logs/laravel.log | grep -A 5 -B 5 "ERROR\|exception" | tail -20

echo ""
echo "6. Verificando middleware TenantMiddleware"
grep -n "is_active\|status" app/Http/Middleware/TenantMiddleware.php

echo ""
echo "=== FIN DIAGNÓSTICO ==="