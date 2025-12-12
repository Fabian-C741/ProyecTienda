#!/bin/bash
echo "=============================================="
echo "   DIAGNÓSTICO COMPLETO DEL SISTEMA"
echo "=============================================="
echo "Timestamp: $(date)"
echo ""

echo "1. VERIFICANDO ESTADO DEL REPOSITORIO"
echo "--------------------------------------"
git status
echo ""
git log --oneline -3
echo ""

echo "2. VERIFICANDO RUTAS DEFINIDAS"
echo "-------------------------------"
echo "Rutas vendedor:"
php artisan route:list | grep vendedor | head -10
echo ""
echo "Rutas super-admin:"
php artisan route:list | grep super-admin | head -5
echo ""

echo "3. VERIFICANDO CONTROLADORES"
echo "-----------------------------"
echo "TenantProductController vistas:"
grep -n "view(" app/Http/Controllers/Tenant/TenantProductController.php || echo "ERROR: No found"
echo ""
echo "TenantOrderController vistas:"
grep -n "view(" app/Http/Controllers/Tenant/TenantOrderController.php || echo "ERROR: No found"
echo ""
echo "TenantSettingsController vistas:"
grep -n "view(" app/Http/Controllers/Tenant/TenantSettingsController.php || echo "ERROR: No found"
echo ""

echo "4. VERIFICANDO VISTAS EXISTEN"
echo "------------------------------"
echo "Estructura vendedor/:"
find resources/views/vendedor/ -name "*.blade.php" | head -10
echo ""
echo "¿Existe carpeta tenant? (no debería):"
ls -la resources/views/tenant/ 2>/dev/null || echo "OK: carpeta tenant no existe"
echo ""

echo "5. VERIFICANDO MIDDLEWARE"
echo "-------------------------"
echo "TenantMiddleware línea is_active:"
grep -n "is_active" app/Http/Middleware/TenantMiddleware.php || echo "ERROR: No found"
echo ""

echo "6. VERIFICANDO VISTAS COMPILADAS"
echo "---------------------------------"
echo "Archivos compilados actuales:"
ls -la storage/framework/views/ | wc -l
echo "Archivos compilados:"
ls storage/framework/views/*.php 2>/dev/null | head -5 || echo "No compiled views"
echo ""

echo "7. VERIFICANDO BASE DE DATOS"
echo "-----------------------------"
echo "Usuario de prueba (ID 15):"
php artisan tinker --execute="
\$user = App\\Models\\User::find(15);
if(\$user) {
  echo 'ID: ' . \$user->id . PHP_EOL;
  echo 'Email: ' . \$user->email . PHP_EOL;
  echo 'Role: ' . \$user->role . PHP_EOL;
  echo 'Tenant ID: ' . \$user->tenant_id . PHP_EOL;
  if(\$user->tenant) {
    echo 'Tenant Name: ' . \$user->tenant->name . PHP_EOL;
    echo 'Tenant Active: ' . (\$user->tenant->is_active ? 'YES' : 'NO') . PHP_EOL;
  } else {
    echo 'ERROR: No tenant found' . PHP_EOL;
  }
} else {
  echo 'ERROR: User 15 not found' . PHP_EOL;
}
"
echo ""

echo "8. ÚLTIMOS ERRORES EN LOGS"
echo "---------------------------"
echo "Últimos 5 errores:"
tail -n 200 storage/logs/laravel.log | grep -B 2 -A 8 "ERROR\|exception" | tail -20
echo ""

echo "9. TESTING RUTAS INTERNAS"
echo "--------------------------"
echo "Test /vendedor/productos:"
curl -s -w "Status: %{http_code}" "http://localhost/vendedor/productos" -H "Host: ingreso-tienda.kcrsf.com" | head -3
echo ""
echo "Test /vendedor/configuracion:"
curl -s -w "Status: %{http_code}" "http://localhost/vendedor/configuracion" -H "Host: ingreso-tienda.kcrsf.com" | head -3
echo ""

echo "10. VERIFICANDO CONFIGURACIÓN"
echo "------------------------------"
echo "APP_ENV: $(php -r "echo env('APP_ENV');")"
echo "APP_DEBUG: $(php -r "echo env('APP_DEBUG');")"
echo "DB_CONNECTION: $(php -r "echo env('DB_CONNECTION');")"
echo ""

echo "=============================================="
echo "   FIN DEL DIAGNÓSTICO"
echo "=============================================="