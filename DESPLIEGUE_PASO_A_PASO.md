# GUÍA DE DESPLIEGUE - PASO A PASO

## ✅ Ya tienes:
- Subdominio creado en Hostinger
- Base de datos MySQL creada

## 📝 Información que necesitas tener a mano:

### Base de Datos:
- Nombre de la BD: _______________
- Usuario: _______________
- Contraseña: _______________
- Host: localhost (generalmente)

### Subdominio:
- URL: _______________ (ej: tienda.tudominio.com)

---

## 🚀 PASO 1: Configurar Backend para Producción

### 1.1 Editar archivo .env del backend

Abre `backend\.env` y configura:

```env
APP_NAME="Tu Tienda"
APP_ENV=production
APP_KEY=base64:xxx  # Se generará automáticamente
APP_DEBUG=false
APP_URL=https://tu-subdominio.tudominio.com

LOG_CHANNEL=stack
LOG_LEVEL=error

# IMPORTANTE: Configura tu base de datos
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=nombre_de_tu_bd
DB_USERNAME=usuario_de_tu_bd
DB_PASSWORD=password_de_tu_bd

# Resto de configuración...
BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Email (opcional - puedes configurar después)
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=587
MAIL_USERNAME=tu-email@tudominio.com
MAIL_PASSWORD=tu_password_email
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@tudominio.com"
MAIL_FROM_NAME="${APP_NAME}"

# URLs permitidas para CORS
FRONTEND_URL=https://tu-subdominio.tudominio.com
SANCTUM_STATEFUL_DOMAINS=tu-subdominio.tudominio.com
SESSION_DOMAIN=.tudominio.com
```

### 1.2 Generar Application Key

```powershell
cd backend
php artisan key:generate
```

---

## 🚀 PASO 2: Preparar Archivos para Subir

### 2.1 Compilar Frontend

```powershell
cd frontend

# Crear archivo .env.production
echo "VITE_API_URL=https://tu-subdominio.tudominio.com/api/v1" > .env.production

# Compilar
npm run build
```

Esto creará la carpeta `frontend\dist\` con archivos optimizados.

### 2.2 Optimizar Backend

```powershell
cd ..\backend

# Instalar dependencias de producción
composer install --optimize-autoloader --no-dev

# Limpiar y optimizar
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

---

## 🚀 PASO 3: Subir Archivos a Hostinger

### Opción A: File Manager (Más fácil)

1. **Comprimir archivos localmente:**
   - Comprime la carpeta `backend` → `backend.zip`
   - Comprime la carpeta `frontend\dist` → `frontend.zip`

2. **En Hostinger File Manager:**
   - Ve a `public_html`
   - Crea carpeta `api`
   - Sube `backend.zip` a `public_html/api/`
   - Extrae `backend.zip` dentro de `api`
   - Sube `frontend.zip` a `public_html/`
   - Extrae `frontend.zip` (los archivos van directamente en public_html)

### Opción B: FTP (FileZilla, etc.)

1. **Subir Backend:**
   - Conecta por FTP
   - Sube toda la carpeta `backend` a `public_html/api/`

2. **Subir Frontend:**
   - Sube el CONTENIDO de `frontend/dist/` a `public_html/`
   - (Los archivos, no la carpeta dist)

---

## 🚀 PASO 4: Configurar Archivos en el Servidor

### 4.1 Configurar .htaccess para Laravel (Backend)

Crear/editar `public_html/api/public/.htaccess`:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

### 4.2 Configurar .htaccess para React (Frontend)

Crear `public_html/.htaccess`:

```apache
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

# Comprimir archivos
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript application/json
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
```

### 4.3 Configurar Permisos

En File Manager o por SSH:

```bash
chmod -R 755 public_html/api/storage
chmod -R 755 public_html/api/bootstrap/cache
```

---

## 🚀 PASO 5: Configurar Base de Datos en el Servidor

### 5.1 Por SSH (si tienes acceso):

```bash
cd public_html/api
php artisan migrate --force
php artisan db:seed --force
```

### 5.2 Por phpMyAdmin (alternativa):

1. Ve a phpMyAdmin en Hostinger
2. Selecciona tu base de datos
3. Ve a SQL
4. Ejecuta las migraciones manualmente (necesitarás los archivos SQL)

---

## 🚀 PASO 6: Configurar Subdominio

En el panel de Hostinger:

1. Ve a **Dominios → Subdominios**
2. Tu subdominio debería apuntar a: `public_html`
3. Si quieres que la API esté en un subdominio separado:
   - Crea `api.tudominio.com` → apunta a `public_html/api/public`

---

## 🚀 PASO 7: Activar SSL/HTTPS

1. En Hostinger → SSL
2. Activa SSL para tu subdominio
3. Fuerza HTTPS

Agrega al `.htaccess` principal:

```apache
# Forzar HTTPS
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

---

## ✅ PASO 8: Verificar Instalación

1. **Frontend:** Visita `https://tu-subdominio.tudominio.com`
2. **API:** Visita `https://tu-subdominio.tudominio.com/api/v1/products`
3. **Login:** Usa `admin@tienda.com` / `password`

---

## 🔧 Comandos Útiles en el Servidor

Si tienes acceso SSH:

```bash
# Ver logs
tail -f public_html/api/storage/logs/laravel.log

# Limpiar cache
cd public_html/api
php artisan cache:clear
php artisan config:clear

# Regenerar cache
php artisan config:cache
php artisan route:cache
```

---

## 🆘 Solución de Problemas Comunes

### Error 500 en API:
1. Verifica permisos de `storage` y `bootstrap/cache`
2. Revisa `storage/logs/laravel.log`
3. Verifica credenciales de BD en `.env`

### API no responde:
1. Verifica que `.htaccess` esté en `public_html/api/public/`
2. Verifica que mod_rewrite esté activo

### Frontend muestra página en blanco:
1. Revisa consola del navegador
2. Verifica que VITE_API_URL sea correcto
3. Verifica que `.htaccess` esté en `public_html/`

### Rutas de React no funcionan:
1. Verifica `.htaccess` en raíz
2. Asegúrate que todas las rutas apunten a `index.html`

---

## 📞 ¿Necesitas Ayuda?

Si encuentras algún error, comparte:
1. El mensaje de error completo
2. Qué paso estabas realizando
3. Los logs de `storage/logs/laravel.log`
