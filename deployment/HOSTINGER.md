# Despliegue en Hostinger Premium

Esta guía te ayudará a desplegar la aplicación en un servidor Hostinger Premium.

## 📋 Prerrequisitos

- Cuenta de Hostinger Premium con acceso a:
  - Panel de control (hPanel)
  - MySQL
  - SSH (opcional pero recomendado)
  - Gestor de archivos o FTP
- Dominio o subdominio configurado

## 🚀 Pasos de Instalación

### 1. Configurar Base de Datos

1. Accede a hPanel → Bases de datos MySQL
2. Crea una nueva base de datos:
   - Nombre: `tienda_multitenant`
   - Usuario: crea un usuario con todos los privilegios
   - Anota credenciales

### 2. Preparar Backend (Laravel)

#### En tu computadora local:

```bash
cd backend

# Instalar dependencias
composer install --optimize-autoloader --no-dev

# Generar archivos de configuración
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### Configurar .env para producción:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tusubdominio.tudominio.com

DB_HOST=localhost
DB_DATABASE=nombre_base_datos
DB_USERNAME=usuario_bd
DB_PASSWORD=password_bd

# Configurar correos (SendGrid recomendado)
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=tu_api_key_sendgrid

# Pasarelas de pago (producción)
MERCADOPAGO_PUBLIC_KEY=tu_public_key
MERCADOPAGO_ACCESS_TOKEN=tu_access_token

STRIPE_PUBLIC_KEY=tu_public_key
STRIPE_SECRET_KEY=tu_secret_key

PAYPAL_MODE=live
PAYPAL_CLIENT_ID=tu_client_id
PAYPAL_SECRET=tu_secret
```

### 3. Subir Backend al Servidor

#### Opción A: Via FTP/File Manager

1. Comprime la carpeta `backend` en un ZIP
2. Sube el ZIP al directorio `public_html/api`
3. Extrae el ZIP en el servidor

#### Opción B: Via SSH (Recomendado)

```bash
# Conectarse por SSH
ssh usuario@tudominio.com

# Navegar a directorio
cd public_html
mkdir api
cd api

# Clonar o subir archivos
# Instalar dependencias
composer install --optimize-autoloader --no-dev

# Configurar permisos
chmod -R 755 storage bootstrap/cache
chown -R usuario:usuario storage bootstrap/cache
```

### 4. Configurar .htaccess para Laravel

Crear `.htaccess` en `public_html/api/public/`:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /api/
    
    # Redirigir a public
    RewriteCond %{REQUEST_URI} !^/api/public/
    RewriteRule ^(.*)$ /api/public/$1 [L]
    
    # Manejar rutas Laravel
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^ index.php [L]
</IfModule>
```

### 5. Migrar Base de Datos

```bash
cd public_html/api
php artisan migrate --force
php artisan db:seed --force
```

### 6. Preparar Frontend (React)

#### En tu computadora local:

```bash
cd frontend

# Configurar variables de producción
echo "VITE_API_URL=https://tusubdominio.tudominio.com/api/v1" > .env.production

# Compilar para producción
npm run build
```

Esto genera la carpeta `dist/` con archivos optimizados.

### 7. Subir Frontend

1. Sube el contenido de `frontend/dist/` a `public_html/`
2. Asegúrate de que `index.html` esté en la raíz

### 8. Configurar .htaccess para React (SPA)

Crear `.htaccess` en `public_html/`:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /
    
    # No reescribir archivos o directorios existentes
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    
    # Todas las rutas a index.html (SPA)
    RewriteRule ^ index.html [L]
</IfModule>

# Comprimir archivos
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript
</IfModule>

# Cache
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
</IfModule>
```

### 9. Configurar Subdominio (Opcional)

Si quieres usar `api.tudominio.com`:

1. En hPanel → Dominios → Subdominios
2. Crear subdominio `api`
3. Apuntar a `public_html/api/public`

## 🔧 Configuración de Servicios Externos

### SendGrid (Envío de Emails)

1. Crear cuenta en [SendGrid](https://sendgrid.com)
2. Generar API Key
3. Agregar a `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=SG.xxx
```

### Mercado Pago

1. Ir a [Mercado Pago Developers](https://www.mercadopago.com/developers)
2. Obtener credenciales de producción
3. Configurar webhooks apuntando a: `https://tudominio.com/api/v1/webhooks/mercadopago`

### Stripe

1. Ir a [Stripe Dashboard](https://dashboard.stripe.com)
2. Obtener API keys de producción
3. Configurar webhooks: `https://tudominio.com/api/v1/webhooks/stripe`

### PayPal

1. Ir a [PayPal Developer](https://developer.paypal.com)
2. Crear app en modo "Live"
3. Obtener Client ID y Secret

## ✅ Verificación Post-Despliegue

1. **Backend**: Visita `https://tudominio.com/api/v1/products`
2. **Frontend**: Visita `https://tudominio.com`
3. **Login**: Prueba con `admin@tienda.com` / `password`

## 🛠️ Comandos Útiles

```bash
# Limpiar cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimizar
php artisan optimize
php artisan config:cache
php artisan route:cache

# Ver logs
tail -f storage/logs/laravel.log
```

## 🔒 Seguridad

- [ ] Cambiar contraseña del super admin
- [ ] Activar SSL/HTTPS en Hostinger
- [ ] Configurar CORS correctamente
- [ ] Validar webhooks de pasarelas de pago
- [ ] Configurar backups automáticos de BD
- [ ] Configurar variables de entorno seguras

## 📊 Monitoreo

- Usar logs de Laravel: `storage/logs/laravel.log`
- Activar error reporting en hPanel
- Configurar notificaciones de SendGrid

## 🆘 Solución de Problemas

### Error 500
- Verificar permisos de `storage` y `bootstrap/cache`
- Revisar `.env` y credenciales de BD
- Ver logs en `storage/logs/laravel.log`

### API no responde
- Verificar configuración de `.htaccess`
- Comprobar que mod_rewrite está activo

### Rutas React no funcionan
- Verificar `.htaccess` en raíz
- Asegurar que todas las rutas apunten a `index.html`
