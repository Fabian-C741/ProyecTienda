# ===============================================
# Script para cambiar entre BD local y remota
# ===============================================

param(
    [Parameter(Mandatory=$true)]
    [ValidateSet('local', 'remote')]
    [string]$Ambiente
)

$backendPath = "d:\Proyectos 2\Tienda_online_multiplataformas\backend"
$envFile = "$backendPath\.env"

Write-Host "================================" -ForegroundColor Cyan
Write-Host "  Cambiar Base de Datos" -ForegroundColor Cyan
Write-Host "================================" -ForegroundColor Cyan
Write-Host ""

switch ($Ambiente) {
    'local' {
        Write-Host "📁 Cambiando a Base de Datos LOCAL..." -ForegroundColor Yellow
        
        if (Test-Path "$backendPath\.env.local") {
            Copy-Item "$backendPath\.env.local" $envFile -Force
            Write-Host "✅ Conectado a BD Local (MySQL local)" -ForegroundColor Green
        } else {
            Copy-Item "$backendPath\.env.example" $envFile -Force
            Write-Host "⚠️  Creado .env desde .env.example" -ForegroundColor Yellow
            Write-Host "   Configura tus datos locales en .env" -ForegroundColor Yellow
        }
    }
    'remote' {
        Write-Host "🌐 Cambiando a Base de Datos REMOTA (Producción)..." -ForegroundColor Yellow
        
        if (Test-Path "$backendPath\.env.remote") {
            Copy-Item "$backendPath\.env.remote" $envFile -Force
            Write-Host "✅ Conectado a BD de Producción (Hostinger)" -ForegroundColor Green
            Write-Host "" -ForegroundColor Yellow
            Write-Host "⚠️  ADVERTENCIA:" -ForegroundColor Red
            Write-Host "   - Estás trabajando con datos REALES de producción" -ForegroundColor Yellow
            Write-Host "   - Los cambios afectarán a usuarios en vivo" -ForegroundColor Yellow
            Write-Host "   - Ten mucho cuidado al hacer pruebas" -ForegroundColor Yellow
        } else {
            Write-Host "❌ Error: No existe .env.remote" -ForegroundColor Red
            Write-Host "   Configura primero el archivo .env.remote con los datos de Hostinger" -ForegroundColor Yellow
            exit 1
        }
    }
}

Write-Host ""
Write-Host "================================" -ForegroundColor Cyan
Write-Host "Configuración actual:" -ForegroundColor Cyan
Write-Host ""

# Mostrar datos de conexión (sin contraseña)
if (Test-Path $envFile) {
    $dbHost = (Get-Content $envFile | Select-String "^DB_HOST=").ToString() -replace "DB_HOST=", ""
    $dbName = (Get-Content $envFile | Select-String "^DB_DATABASE=").ToString() -replace "DB_DATABASE=", ""
    $dbUser = (Get-Content $envFile | Select-String "^DB_USERNAME=").ToString() -replace "DB_USERNAME=", ""
    
    Write-Host "  Host: $dbHost" -ForegroundColor White
    Write-Host "  Database: $dbName" -ForegroundColor White
    Write-Host "  Username: $dbUser" -ForegroundColor White
}

Write-Host "================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "💡 Limpia la caché de Laravel:" -ForegroundColor Cyan
Write-Host "   php artisan config:clear" -ForegroundColor White
Write-Host "   php artisan cache:clear" -ForegroundColor White
Write-Host ""
