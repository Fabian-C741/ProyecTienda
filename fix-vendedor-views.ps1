# Script para corregir las vistas del vendedor
# Este script corrige todas las referencias problemáticas en las vistas

Write-Host "🔧 Iniciando corrección de vistas del vendedor..." -ForegroundColor Green

# Comandos para ejecutar en el servidor
$commands = @(
    # 1. Limpiar cachés
    "cd /home/u464516792/domains/ingreso-tienda.kcrsf.com/public_html/backend",
    "rm -rf storage/framework/views/*",
    "php artisan view:clear",
    "php artisan config:clear",
    "php artisan route:clear",
    
    # 2. Corregir @extends en todas las vistas
    "sed -i 's/@extends(.*tenant\.layout.*/@extends(''vendedor.layout'')/g' resources/views/vendedor/productos/index.blade.php",
    "sed -i 's/@extends(.*tenant\.layout.*/@extends(''vendedor.layout'')/g' resources/views/vendedor/productos/create.blade.php",
    "sed -i 's/@extends(.*tenant\.layout.*/@extends(''vendedor.layout'')/g' resources/views/vendedor/productos/edit.blade.php",
    "sed -i 's/@extends(.*tenant\.layout.*/@extends(''vendedor.layout'')/g' resources/views/vendedor/dashboard/index.blade.php",
    "sed -i 's/@extends(.*tenant\.layout.*/@extends(''vendedor.layout'')/g' resources/views/vendedor/configuracion/index.blade.php",
    "sed -i 's/@extends(.*tenant\.layout.*/@extends(''vendedor.layout'')/g' resources/views/vendedor/dashboard.blade.php",
    "sed -i 's/@extends(.*tenant\.layout.*/@extends(''vendedor.layout'')/g' resources/views/vendedor/pedidos/index.blade.php",
    "sed -i 's/@extends(.*tenant\.layout.*/@extends(''vendedor.layout'')/g' resources/views/vendedor/pedidos/show.blade.php",
    
    # 3. Corregir ruta tenant.dashboard a vendedor.dashboard
    "sed -i 's/tenant\.dashboard/vendedor.dashboard/g' resources/views/vendedor/layout.blade.php",
    
    # 4. Verificar que las rutas estén correctas
    "php artisan route:list | grep vendedor"
)

foreach ($command in $commands) {
    Write-Host "Ejecutando: $command" -ForegroundColor Yellow
    ssh u464516792@br-asc-web1885.main-hosting.eu -p 65002 $command
    
    if ($LASTEXITCODE -ne 0) {
        Write-Host "❌ Error en comando: $command" -ForegroundColor Red
    } else {
        Write-Host "✅ Comando exitoso" -ForegroundColor Green
    }
    Start-Sleep -Seconds 1
}

Write-Host "🎉 Corrección de vistas completada!" -ForegroundColor Green