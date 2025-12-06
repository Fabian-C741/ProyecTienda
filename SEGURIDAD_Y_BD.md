# 🔒 GUÍA DE SEGURIDAD Y CONFIGURACIÓN DE BASE DE DATOS

## 🎯 Configuración Segura de Base de Datos en Hostinger

### PASO 1: Crear Base de Datos en Hostinger

1. **Accede al panel de Hostinger (hPanel)**
2. Ve a **Bases de datos** → **Bases de datos MySQL**
3. Haz clic en **"Crear nueva base de datos"**

**IMPORTANTE - Configuración Segura:**
```
✅ Nombre de BD: u123456789_tienda_prod
✅ Usuario: u123456789_admin_prod
✅ Contraseña: Genera una FUERTE (usa el generador de Hostinger)
✅ Host: localhost (NO cambies esto)
```

**⚠️ NUNCA uses:**
- ❌ Contraseñas débiles (123456, password, etc.)
- ❌ Usuario "root"
- ❌ Nombre de BD obvio como "tienda" o "shop"
- ❌ Permisos innecesarios

### PASO 2: Anotar Credenciales (Guárdalas en un lugar SEGURO)

```
Host: localhost
Puerto: 3306
Base de datos: ________________
Usuario: ________________
Contraseña: ________________
```

**Opciones seguras para guardar:**
- ✅ Gestor de contraseñas (LastPass, 1Password, Bitwarden)
- ✅ Archivo encriptado local
- ❌ NUNCA en Git
- ❌ NUNCA en email
- ❌ NUNCA en notas públicas

---

## 🛡️ PASO 3: Configurar .env SEGURO en Hostinger

### Opción A: Por File Manager (Más fácil)

1. Ve a **File Manager** en Hostinger
2. Navega a `public_html/api/`
3. Crea archivo `.env`
4. Pega esta configuración SEGURA:

```env
# === CONFIGURACIÓN DE PRODUCCIÓN ===
# NO compartas este archivo con nadie

APP_NAME="Tu Tienda"
APP_ENV=production
APP_KEY=base64:XXXXX  # Se genera con: php artisan key:generate
APP_DEBUG=false  # ⚠️ SIEMPRE false en producción
APP_URL=https://tusubdominio.tudominio.com

# === SEGURIDAD ===
LOG_CHANNEL=daily
LOG_LEVEL=error  # Solo errores, no debug
LOG_DEPRECATIONS_CHANNEL=null

# === BASE DE DATOS ===
DB_CONNECTION=mysql
DB_HOST=localhost  # ⚠️ NO cambiar en Hostinger
DB_PORT=3306
DB_DATABASE=tu_nombre_bd_real
DB_USERNAME=tu_usuario_bd_real
DB_PASSWORD=tu_password_bd_real

# === CACHE Y SESIONES ===
BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database  # Cambiar a 'database' para producción
SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=true  # Encriptar sesiones

# === CORS Y DOMINIO ===
FRONTEND_URL=https://tusubdominio.tudominio.com
SANCTUM_STATEFUL_DOMAINS=tusubdominio.tudominio.com
SESSION_DOMAIN=.tudominio.com  # Nota el punto inicial

# === EMAIL (SendGrid - Recomendado) ===
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=SG.tu_api_key_sendgrid
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@tudominio.com"
MAIL_FROM_NAME="${APP_NAME}"

# === PASARELAS DE PAGO (Producción) ===
# Mercado Pago
MERCADOPAGO_PUBLIC_KEY=APP_USR-xxxxx-prod
MERCADOPAGO_ACCESS_TOKEN=APP_USR-xxxxx-prod
MERCADOPAGO_WEBHOOK_SECRET=tu_secret_webhook

# Stripe
STRIPE_PUBLIC_KEY=pk_live_xxxxx
STRIPE_SECRET_KEY=sk_live_xxxxx
STRIPE_WEBHOOK_SECRET=whsec_xxxxx

# PayPal
PAYPAL_MODE=live  # NO sandbox en producción
PAYPAL_CLIENT_ID=tu_client_id_live
PAYPAL_SECRET=tu_secret_live

# === LÍMITES DE SEGURIDAD ===
THROTTLE_REQUESTS=60
THROTTLE_DECAY_MINUTES=1
```

### Opción B: Por SSH (Más seguro)

```bash
# Conectar por SSH
ssh usuario@tudominio.com

# Navegar al directorio
cd public_html/api

# Copiar ejemplo
cp .env.example .env

# Editar con nano (más fácil) o vim
nano .env

# Configurar las variables
# Guardar: Ctrl+O, Enter, Ctrl+X
```

---

## 🔐 PASO 4: Generar APP_KEY Segura

**IMPORTANTE:** La APP_KEY encripta tus datos. DEBE ser única y segura.

### Por SSH:
```bash
cd public_html/api
php artisan key:generate
```

### Sin SSH (Manual):
```bash
# En tu PC, genera una key:
php artisan key:generate --show

# Copia el resultado (base64:xxxxxx)
# Pégalo en .env en Hostinger
```

---

## 🛡️ MEDIDAS DE SEGURIDAD ADICIONALES

### 1. Proteger Archivos Sensibles

Crear/editar `public_html/api/.htaccess`:

```apache
# Denegar acceso a archivos sensibles
<FilesMatch "^\.env">
    Order allow,deny
    Deny from all
</FilesMatch>

<FilesMatch "\.blade\.php$">
    Order allow,deny
    Deny from all
</FilesMatch>

# Proteger directorios
Options -Indexes

# Prevenir inyección de archivos
<FilesMatch "\.(php|php\.)">
    Order Allow,Deny
    Deny from all
</FilesMatch>

<FilesMatch "^(index\.php)$">
    Order Allow,Deny
    Allow from all
</FilesMatch>
```

### 2. Configurar Permisos Correctos

```bash
# Archivos: solo lectura para servidor
chmod 644 public_html/api/.env
chmod 644 public_html/api/composer.json

# Carpetas: lectura y ejecución
chmod 755 public_html/api/storage
chmod 755 public_html/api/bootstrap/cache

# Subcarpetas de storage
chmod -R 775 public_html/api/storage
chmod -R 775 public_html/api/bootstrap/cache
```

### 3. Deshabilitar Funciones Peligrosas de PHP

En `php.ini` o `.user.ini`:

```ini
disable_functions = exec,passthru,shell_exec,system,proc_open,popen,curl_exec,curl_multi_exec,parse_ini_file,show_source
expose_php = Off
display_errors = Off
```

### 4. Configurar CORS Seguro

Crear `public_html/api/app/Http/Middleware/Cors.php`:

```php
<?php

namespace App\Http\Middleware;

use Closure;

class Cors
{
    public function handle($request, Closure $next)
    {
        $allowedOrigins = [
            'https://tusubdominio.tudominio.com',
            // Agrega otros dominios permitidos
        ];

        $origin = $request->header('Origin');

        if (in_array($origin, $allowedOrigins)) {
            return $next($request)
                ->header('Access-Control-Allow-Origin', $origin)
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, X-Tenant-ID')
                ->header('Access-Control-Allow-Credentials', 'true');
        }

        return $next($request);
    }
}
```

### 5. Rate Limiting (Protección contra ataques)

Ya configurado en `routes/api.php`, pero verifica:

```php
// En config/sanctum.php
'limiter' => 60, // 60 requests por minuto

// En routes/api.php
Route::middleware(['throttle:api'])->group(function () {
    // Tus rutas
});
```

---

## 🔍 PASO 5: Ejecutar Migraciones de Forma Segura

### Por SSH:
```bash
cd public_html/api

# Verificar conexión a BD
php artisan tinker
>>> DB::connection()->getPdo();
>>> exit

# Ejecutar migraciones
php artisan migrate --force

# Crear usuario admin SEGURO
php artisan tinker
>>> $admin = \App\Models\User::create([
    'name' => 'Administrador',
    'email' => 'admin@tudominio.com',
    'password' => bcrypt('TuPasswordSuperSeguro123!@#'),
    'is_active' => true,
]);
>>> $admin->assignRole('super_admin');
>>> exit
```

### Sin SSH (phpMyAdmin):
1. Ve a phpMyAdmin en Hostinger
2. Selecciona tu base de datos
3. Importa las migraciones manualmente (más complejo)

---

## 🚨 VALIDACIÓN DE SEGURIDAD

### Checklist de Seguridad:

- [ ] APP_DEBUG=false en .env
- [ ] APP_KEY generada y única
- [ ] Contraseña de BD fuerte (16+ caracteres)
- [ ] Usuario de BD NO es "root"
- [ ] Permisos 644 en .env
- [ ] Permisos 755 en storage/
- [ ] .htaccess protege .env
- [ ] CORS configurado con dominios específicos
- [ ] Rate limiting activo
- [ ] SSL/HTTPS activado
- [ ] Contraseña de admin cambiada
- [ ] Logs configurados (solo errors)
- [ ] Webhooks de pago con secretos

---

## 🔒 PREVENCIÓN DE VULNERABILIDADES COMUNES

### 1. SQL Injection
✅ **Protegido:** Laravel usa Eloquent/Query Builder que previene automáticamente
```php
// ✅ SEGURO (usa prepared statements)
User::where('email', $email)->first();

// ❌ NUNCA hagas esto
DB::raw("SELECT * FROM users WHERE email = '$email'");
```

### 2. XSS (Cross-Site Scripting)
✅ **Protegido:** Laravel escapa automáticamente en Blade
```php
// ✅ SEGURO en API responses
return response()->json(['name' => $user->name]); // Escapado automático
```

### 3. CSRF
✅ **Protegido:** Sanctum maneja tokens automáticamente
```php
// Ya configurado en sanctum.php
```

### 4. Mass Assignment
✅ **Protegido:** Define $fillable en modelos
```php
// En cada modelo
protected $fillable = ['campo1', 'campo2']; // Solo estos campos
protected $guarded = ['id', 'is_admin']; // Proteger estos
```

### 5. Exposición de Información
```env
# ✅ SIEMPRE en producción
APP_DEBUG=false
LOG_LEVEL=error
```

---

## 📊 MONITOREO Y LOGS

### Ver logs de errores:
```bash
# Por SSH
tail -f public_html/api/storage/logs/laravel.log

# Por File Manager
# Descarga: storage/logs/laravel.log
```

### Configurar alertas:
```php
// En App\Exceptions\Handler.php
public function register()
{
    $this->reportable(function (Throwable $e) {
        if (app()->environment('production')) {
            // Enviar email o notificación
            Mail::to('admin@tudominio.com')->send(new ErrorNotification($e));
        }
    });
}
```

---

## 🔄 BACKUP DE BASE DE DATOS

### Backup Manual (Recomendado semanal):

1. **phpMyAdmin:**
   - Selecciona tu BD
   - Exportar → Método: Rápido → SQL
   - Descargar

2. **Por SSH:**
```bash
mysqldump -u usuario -p nombre_bd > backup_$(date +%Y%m%d).sql
```

3. **Automatizado (cron job):**
```bash
# En cPanel/Hostinger → Cron Jobs
0 2 * * 0 mysqldump -u usuario -p'password' nombre_bd > ~/backups/backup_$(date +\%Y\%m\%d).sql
```

---

## ✅ VERIFICACIÓN FINAL

### Test de seguridad:

1. **Intenta acceder a .env:**
   - Visita: `https://tudominio.com/api/.env`
   - Debería dar: 403 Forbidden

2. **Verifica SSL:**
   - Visita: `https://tudominio.com`
   - Debe mostrar candado verde

3. **Test de API:**
```bash
# Debería funcionar
curl https://tudominio.com/api/v1/products

# Debería bloquear después de muchos requests
for i in {1..100}; do curl https://tudominio.com/api/v1/login; done
```

---

## 🆘 PROBLEMAS COMUNES

### Error: "SQLSTATE[HY000] [2002] Connection refused"
```bash
# Verifica:
1. DB_HOST=localhost (no 127.0.0.1)
2. Credenciales correctas en .env
3. Base de datos existe
```

### Error: "No application encryption key has been set"
```bash
php artisan key:generate
```

### Error: "Access denied for user"
```bash
# Verifica credenciales en .env
# Verifica permisos del usuario en phpMyAdmin
```

---

## 📞 CONTACTO DE EMERGENCIA

Si detectas una brecha de seguridad:

1. **Inmediatamente:**
   - Cambia contraseñas de BD
   - Regenera APP_KEY
   - Revisa logs
   
2. **Restaura backup** si es necesario

3. **Actualiza dependencias:**
```bash
composer update
npm audit fix
```
