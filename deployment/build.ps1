# Script de build para despliegue

Write-Host "Compilando proyecto para produccion..." -ForegroundColor Green

# Backend
Write-Host "`nPreparando Backend..." -ForegroundColor Yellow
Set-Location backend

if (Test-Path "composer.lock") {
    composer install --optimize-autoloader --no-dev
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    Write-Host "Backend preparado correctamente" -ForegroundColor Green
} else {
    Write-Host "Error: No se encontro composer.lock. Ejecuta 'composer install' primero." -ForegroundColor Red
}

Set-Location ..

# Frontend
Write-Host "`nPreparando Frontend..." -ForegroundColor Yellow
Set-Location frontend

if (Test-Path "package.json") {
    npm run build
    Write-Host "Frontend compilado correctamente en dist/" -ForegroundColor Green
} else {
    Write-Host "Error: No se encontro package.json" -ForegroundColor Red
}

Set-Location ..

Write-Host "`n=== Compilacion completada ===" -ForegroundColor Green
Write-Host "Backend: backend/" -ForegroundColor Cyan
Write-Host "Frontend: frontend/dist/" -ForegroundColor Cyan
Write-Host "`nSigue las instrucciones en deployment/HOSTINGER.md para subir al servidor" -ForegroundColor Yellow
