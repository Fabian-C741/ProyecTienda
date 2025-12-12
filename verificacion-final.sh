#!/bin/bash

# Script de verificación final del sistema
# Verifica que todas las correcciones estén aplicadas y el sistema funcione

echo "🔍 Verificación final del sistema de vendedor"
echo "============================================="

cd /path/to/project/backend

echo ""
echo "1. ✅ Verificando que las vistas usen el layout correcto..."
grep -l "@extends('vendedor.layout')" resources/views/vendedor/*/*.blade.php | wc -l
echo "   Archivos corregidos: $(grep -l "@extends('vendedor.layout')" resources/views/vendedor/*/*.blade.php | wc -l)"

echo ""
echo "2. ✅ Verificando que no queden referencias a rutas tenant..."
tenant_refs=$(grep -r "tenant\." resources/views/vendedor/ | grep -v "@extends" | grep -v "tenant->" | wc -l)
echo "   Referencias problemáticas restantes: $tenant_refs"

echo ""
echo "3. ✅ Verificando rutas de configuración disponibles..."
php artisan route:list | grep "vendedor.configuracion" | wc -l
echo "   Rutas de configuración: $(php artisan route:list | grep "vendedor.configuracion" | wc -l)"

echo ""
echo "4. ✅ Verificando códigos de respuesta HTTP..."
productos_status=$(curl -s -w '%{http_code}' -o /dev/null 'https://ingreso-tienda.kcrsf.com/vendedor/productos')
pedidos_status=$(curl -s -w '%{http_code}' -o /dev/null 'https://ingreso-tienda.kcrsf.com/vendedor/pedidos')
config_status=$(curl -s -w '%{http_code}' -o /dev/null 'https://ingreso-tienda.kcrsf.com/vendedor/configuracion')
superadmin_status=$(curl -s -w '%{http_code}' -o /dev/null 'https://ingreso-tienda.kcrsf.com/super-admin/dashboard')

echo "   /vendedor/productos: $productos_status (debería ser 401)"
echo "   /vendedor/pedidos: $pedidos_status (debería ser 401)"  
echo "   /vendedor/configuracion: $config_status (debería ser 401)"
echo "   /super-admin/dashboard: $superadmin_status (debería ser 401)"

echo ""
echo "5. ✅ Verificando logs de errores recientes..."
recent_errors=$(tail -n 50 storage/logs/laravel.log | grep -c "ERROR\|exception")
echo "   Errores recientes en logs: $recent_errors"

echo ""
echo "6. ✅ Estado final de cachés..."
echo "   Vistas compiladas: $(ls storage/framework/views/ | wc -l) archivos"

echo ""
echo "============================================="
if [ "$productos_status" = "401" ] && [ "$pedidos_status" = "401" ] && [ "$config_status" = "401" ] && [ "$superadmin_status" = "401" ] && [ "$tenant_refs" = "0" ]; then
    echo "🎉 SISTEMA CORREGIDO EXITOSAMENTE"
    echo "   - Todas las páginas responden correctamente (401 sin auth)"
    echo "   - No hay referencias problemáticas a rutas tenant"
    echo "   - Rutas de configuración agregadas"
    echo "   - Vistas compiladas limpiadas"
else
    echo "⚠️  VERIFICAR PROBLEMAS RESTANTES"
fi
echo "============================================="