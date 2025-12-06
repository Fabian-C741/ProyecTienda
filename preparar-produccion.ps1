# Script para preparar archivos para producción

Write-Host "=== Preparando proyecto para despliegue ===" -ForegroundColor Green
Write-Host ""

# Variables
$projectRoot = $PSScriptRoot
$backendPath = Join-Path $projectRoot "backend"
$frontendPath = Join-Path $projectRoot "frontend"
$outputPath = Join-Path $projectRoot "deploy-ready"

# Crear carpeta de salida
if (Test-Path $outputPath) {
    Remove-Item -Recurse -Force $outputPath
}
New-Item -ItemType Directory -Path $outputPath | Out-Null

Write-Host "1. Preparando Frontend..." -ForegroundColor Yellow
Set-Location $frontendPath

# Verificar si existe .env.production
if (-not (Test-Path ".env.production")) {
    Write-Host "   Creando .env.production..." -ForegroundColor Cyan
    $apiUrl = Read-Host "   Ingresa la URL de tu API (ej: https://tudominio.com/api/v1)"
    Set-Content -Path ".env.production" -Value "VITE_API_URL=$apiUrl"
}

Write-Host "   Compilando frontend..." -ForegroundColor Cyan
npm run build

if (Test-Path "dist") {
    $frontendOutput = Join-Path $outputPath "frontend"
    Copy-Item -Path "dist\*" -Destination $frontendOutput -Recurse -Force
    Write-Host "   ✓ Frontend listo en: deploy-ready\frontend\" -ForegroundColor Green
} else {
    Write-Host "   ✗ Error al compilar frontend" -ForegroundColor Red
}

Write-Host ""
Write-Host "2. Preparando Backend..." -ForegroundColor Yellow
Set-Location $backendPath

# Verificar .env
if (-not (Test-Path ".env")) {
    Write-Host "   Copiando .env.example a .env..." -ForegroundColor Cyan
    Copy-Item ".env.example" ".env"
    
    Write-Host ""
    Write-Host "   IMPORTANTE: Configura tu .env con:" -ForegroundColor Red
    Write-Host "   - APP_ENV=production" -ForegroundColor Red
    Write-Host "   - APP_DEBUG=false" -ForegroundColor Red
    Write-Host "   - Credenciales de base de datos" -ForegroundColor Red
    Write-Host "   - APP_URL de tu dominio" -ForegroundColor Red
    Write-Host ""
    
    $continue = Read-Host "   ¿Ya configuraste .env? (s/n)"
    if ($continue -ne "s") {
        Write-Host "   Por favor, configura .env y vuelve a ejecutar este script" -ForegroundColor Yellow
        Set-Location $projectRoot
        exit
    }
}

Write-Host "   Instalando dependencias de producción..." -ForegroundColor Cyan
composer install --optimize-autoloader --no-dev

Write-Host "   Limpiando cache..." -ForegroundColor Cyan
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

Write-Host "   Generando caches de producción..." -ForegroundColor Cyan
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Copiar backend
$backendOutput = Join-Path $outputPath "backend"
Write-Host "   Copiando archivos del backend..." -ForegroundColor Cyan

$excludeFolders = @("node_modules", ".git", "tests", "storage\logs\*")
Copy-Item -Path $backendPath -Destination $backendOutput -Recurse -Force

Write-Host "   ✓ Backend listo en: deploy-ready\backend\" -ForegroundColor Green

Write-Host ""
Write-Host "3. Creando archivos de configuración..." -ForegroundColor Yellow

# .htaccess para frontend
$htaccessFrontend = @"
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /
    
    # No reescribir API
    RewriteCond %{REQUEST_URI} !^/api/
    
    # No reescribir archivos o directorios existentes
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    
    # Todas las rutas a index.html (SPA)
    RewriteRule ^ index.html [L]
</IfModule>

# Forzar HTTPS
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Comprimir archivos
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript
</IfModule>

# Cache
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
</IfModule>
"@

Set-Content -Path (Join-Path $frontendOutput ".htaccess") -Value $htaccessFrontend

# Crear archivo de instrucciones
$instructions = @"
=== INSTRUCCIONES DE DESPLIEGUE ===

ESTRUCTURA EN HOSTINGER:

public_html/
├── .htaccess           (del frontend)
├── index.html          (del frontend)
├── assets/             (del frontend)
└── api/
    └── (todo el contenido de backend aquí)

PASOS:

1. SUBIR FRONTEND:
   - Ve a public_html/
   - Sube TODOS los archivos de deploy-ready\frontend\
   - Asegúrate que .htaccess esté ahí

2. SUBIR BACKEND:
   - Crea carpeta: public_html/api/
   - Sube TODOS los archivos de deploy-ready\backend\
   - Verifica que .env esté configurado correctamente

3. CONFIGURAR PERMISOS (File Manager o SSH):
   - public_html/api/storage → 755
   - public_html/api/bootstrap/cache → 755

4. MIGRAR BASE DE DATOS (por SSH):
   cd public_html/api
   php artisan migrate --force
   php artisan db:seed --force

5. VERIFICAR:
   - Frontend: https://tudominio.com
   - API: https://tudominio.com/api/v1/products
   - Login: admin@tienda.com / password

¿PROBLEMAS?
- Revisa logs en: public_html/api/storage/logs/laravel.log
- Verifica que .htaccess estén en ambas ubicaciones
- Confirma credenciales de BD en .env
"@

Set-Content -Path (Join-Path $outputPath "INSTRUCCIONES.txt") -Value $instructions

Write-Host ""
Write-Host "=== ✓ PREPARACIÓN COMPLETADA ===" -ForegroundColor Green
Write-Host ""
Write-Host "Archivos listos en: $outputPath" -ForegroundColor Cyan
Write-Host ""
Write-Host "Siguiente paso:" -ForegroundColor Yellow
Write-Host "1. Comprime las carpetas 'frontend' y 'backend'" -ForegroundColor White
Write-Host "2. Sube a Hostinger según INSTRUCCIONES.txt" -ForegroundColor White
Write-Host "3. Lee: DESPLIEGUE_PASO_A_PASO.md para más detalles" -ForegroundColor White
Write-Host ""

Set-Location $projectRoot
