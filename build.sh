#!/bin/bash
set -e

echo "🚀 Iniciando deployment..."
echo "📍 Directorio actual: $(pwd)"

# Backend
echo ""
echo "📦 Instalando dependencias de backend..."
cd backend

# Verificar si composer existe
if ! command -v composer &> /dev/null; then
    echo "⚠️  Composer no encontrado, usando ruta completa..."
    COMPOSER="/usr/local/bin/composer"
else
    COMPOSER="composer"
fi

$COMPOSER install --no-dev --optimize-autoloader --no-interaction --prefer-dist

echo ""
echo "🔧 Optimizando Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo ""
echo "🗄️ Ejecutando migraciones..."
php artisan migrate --force

echo ""
echo "🔐 Configurando permisos..."
chmod -R 775 storage bootstrap/cache

echo ""
echo "✅ Deployment completado exitosamente"
echo "🌐 Verifica: https://tudominio.com/api/health"
