# 🔒 SEGURIDAD Y BASE DE DATOS - GUÍA RÁPIDA

## ✅ LO QUE ACABAMOS DE HACER

Acabo de reforzar la seguridad de tu plataforma multi-tenant. Aquí está todo lo implementado:

---

## 📁 ARCHIVOS CREADOS

### 1. **Documentación de Seguridad**
- ✅ `SEGURIDAD_Y_BD.md` - Guía completa de seguridad y configuración
- ✅ `CONEXION_BD.md` - Paso a paso para conectar base de datos en Hostinger
- ✅ `SECURITY_CHECKLIST.md` - 200+ checks de seguridad antes de producción
- ✅ `README_SEGURIDAD.md` - Este archivo (resumen rápido)

### 2. **Middlewares de Seguridad**
- ✅ `backend/app/Http/Middleware/SecurityHeaders.php` - Headers HTTP seguros
- ✅ `backend/app/Http/Middleware/Cors.php` - Control de CORS
- ✅ `backend/app/Http/Middleware/ValidateTenant.php` - Validación multi-tenant
- ✅ `backend/app/Http/Kernel.php` - Registro de middlewares

### 3. **Configuración**
- ✅ `backend/config/cors.php` - Configuración CORS
- ✅ `backend/.env.production.example` - Template de .env seguro
- ✅ `backend/public/.htaccess` - Protección Apache con 15+ reglas

### 4. **Herramientas**
- ✅ `backend/app/Console/Commands/SecurityCheck.php` - Comando de validación
- ✅ `backend/app/Http/Controllers/Api/HealthController.php` - Health check endpoint
- ✅ `validar-seguridad.ps1` - Script de validación local

---

## 🚀 CÓMO USAR (INICIO RÁPIDO)

### **PASO 1: Validar Localmente**
```powershell
# En tu PC, ejecuta:
cd "d:\Proyectos 2\Tienda_online_multiplataformas"
.\validar-seguridad.ps1
```

Esto verificará:
- ✓ Archivos críticos presentes
- ✓ .env no está en Git
- ✓ Dependencias instaladas
- ✓ Sintaxis PHP correcta

---

### **PASO 2: Preparar Base de Datos en Hostinger**

1. **Accede a hPanel de Hostinger**
2. Ve a **Bases de datos → MySQL**
3. **Crear nueva base de datos:**
   ```
   Nombre: u123456789_tienda_prod
   Usuario: u123456789_admin_prod
   Contraseña: [GENERA UNA FUERTE]
   ```

4. **Anota las credenciales** en un lugar seguro (gestor de contraseñas)

📖 **Guía detallada:** `CONEXION_BD.md`

---

### **PASO 3: Configurar .env en Servidor**

1. **Por File Manager:**
   - Ve a `public_html/api/`
   - Crea archivo `.env`
   - Copia contenido de `.env.production.example`
   - Modifica:
     ```env
     DB_HOST=localhost
     DB_DATABASE=tu_bd_real
     DB_USERNAME=tu_usuario_real
     DB_PASSWORD=tu_password_real
     APP_URL=https://tudominio.com
     ```

2. **Generar APP_KEY:**
   ```bash
   # Por SSH:
   php artisan key:generate
   
   # O local y copiar:
   php artisan key:generate --show
   ```

📖 **Guía paso a paso:** `SEGURIDAD_Y_BD.md` (páginas 3-7)

---

### **PASO 4: Ejecutar Migraciones**

```bash
# Por SSH en servidor:
cd public_html/api

# Verificar conexión
php artisan tinker
>>> DB::connection()->getPdo();
>>> exit

# Ejecutar migraciones
php artisan migrate --force

# Crear roles
php artisan db:seed --class=RolesAndPermissionsSeeder --force
```

📖 **Sin SSH:** Ver `CONEXION_BD.md` sección "Sin SSH"

---

### **PASO 5: Verificar Seguridad en Servidor**

```bash
# Ejecutar check de seguridad
php artisan security:check
```

Esto verificará:
- ✓ APP_DEBUG=false
- ✓ Conexión a BD
- ✓ Permisos de archivos
- ✓ Middlewares activos
- ✓ Pasarelas de pago

---

### **PASO 6: Test de Conexión**

**Crear archivo temporal** `public_html/test-db.php`:
```php
<?php
$host = 'localhost';
$database = 'TU_BD';
$username = 'TU_USUARIO';
$password = 'TU_PASSWORD';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    echo "✅ ¡Conexión exitosa!";
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
```

Visita: `https://tudominio.com/test-db.php`

⚠️ **ELIMINAR después de verificar**

---

### **PASO 7: Health Check**

Accede a: `https://tudominio.com/api/health`

Debería responder:
```json
{
  "status": "ok",
  "database": "connected",
  "storage": "writable",
  "cache": "working"
}
```

---

## 🛡️ PROTECCIONES IMPLEMENTADAS

### **1. Prevención de Vulnerabilidades**

| Vulnerabilidad | Protección | Archivo |
|----------------|------------|---------|
| SQL Injection | ✅ Eloquent ORM (prepared statements) | Modelos |
| XSS | ✅ Headers + escapado automático | SecurityHeaders.php |
| CSRF | ✅ Sanctum tokens | routes/api.php |
| Clickjacking | ✅ X-Frame-Options | SecurityHeaders.php |
| MIME Sniffing | ✅ X-Content-Type-Options | SecurityHeaders.php |
| Man-in-the-Middle | ✅ HSTS + HTTPS forzado | .htaccess |

### **2. Control de Acceso**

- ✅ Rate Limiting: 60 requests/minuto
- ✅ Login throttling: 5 intentos máximo
- ✅ CORS: Solo dominios permitidos
- ✅ Multi-tenant isolation
- ✅ Roles: super_admin, tenant_admin, customer

### **3. Protección de Archivos**

- ✅ `.env` inaccesible desde web
- ✅ `.blade.php` protegidos
- ✅ Directorio `storage/` protegido
- ✅ Sin listado de directorios
- ✅ Permisos correctos (644/755)

---

## 📊 CHECKLIST RÁPIDO

Antes de ir a producción:

- [ ] Script `validar-seguridad.ps1` ejecutado ✅
- [ ] Base de datos creada en Hostinger
- [ ] `.env` configurado (NO en Git)
- [ ] `APP_KEY` generada
- [ ] Conexión a BD verificada
- [ ] Migraciones ejecutadas
- [ ] Usuario admin creado
- [ ] `php artisan security:check` ejecutado
- [ ] Health check funciona
- [ ] SSL activado (https://)
- [ ] Cambios pushed a Git

📖 **Checklist completo:** `SECURITY_CHECKLIST.md` (200+ items)

---

## 🔍 COMANDOS ÚTILES

### **En tu PC (local):**
```powershell
# Validar seguridad
.\validar-seguridad.ps1

# Generar APP_KEY
php artisan key:generate --show

# Push a Git
git add .
git commit -m "Seguridad reforzada"
git push origin main
```

### **En Hostinger (SSH):**
```bash
# Navegar al proyecto
cd public_html/api

# Verificar conexión BD
php artisan tinker
>>> DB::connection()->getPdo();

# Ejecutar migraciones
php artisan migrate --force

# Check de seguridad
php artisan security:check

# Ver logs
tail -f storage/logs/laravel.log
```

---

## 🆘 SOLUCIÓN DE PROBLEMAS

### ❌ "Connection refused"
```env
# Verifica en .env:
DB_HOST=localhost  # NO 127.0.0.1
DB_PORT=3306
```

### ❌ "Access denied"
- Ve a Bases de datos en Hostinger
- Verifica usuario y contraseña
- Regenera contraseña si es necesario

### ❌ "APP_KEY not set"
```bash
php artisan key:generate
```

### ❌ Error 500
```bash
# Ver logs:
tail -f storage/logs/laravel.log

# O descarga: storage/logs/laravel.log
```

📖 **Más soluciones:** `CONEXION_BD.md` sección "Problemas Comunes"

---

## 📞 RECURSOS

### **Documentación Creada:**
1. `SEGURIDAD_Y_BD.md` → Configuración completa (20+ páginas)
2. `CONEXION_BD.md` → Conexión paso a paso
3. `SECURITY_CHECKLIST.md` → 200+ checks
4. Este archivo → Resumen rápido

### **Scripts:**
1. `validar-seguridad.ps1` → Validación local
2. `php artisan security:check` → Validación en servidor

### **Endpoints:**
1. `/api/health` → Estado del sistema
2. `/api/version` → Información de versión

### **Tests Online:**
- https://observatory.mozilla.org/ → Headers de seguridad
- https://securityheaders.com/ → Análisis de headers
- https://www.ssllabs.com/ssltest/ → Test de SSL

---

## ✅ SIGUIENTE PASO

### **Ahora mismo, ejecuta:**

```powershell
cd "d:\Proyectos 2\Tienda_online_multiplataformas"
.\validar-seguridad.ps1
```

**Si todo está OK (puntuación > 95%):**
1. Lee `CONEXION_BD.md`
2. Configura la base de datos en Hostinger
3. Sigue `SECURITY_CHECKLIST.md`

**Si hay errores:**
1. Revisa los errores mostrados
2. Corrige según las indicaciones
3. Vuelve a ejecutar el script

---

## 🎯 OBJETIVO FINAL

Tu plataforma tendrá:
- ✅ **0 vulnerabilidades conocidas**
- ✅ **Base de datos conectada de forma segura**
- ✅ **HTTPS forzado**
- ✅ **Headers de seguridad completos**
- ✅ **Protección contra ataques comunes**
- ✅ **Multi-tenant isolation**
- ✅ **Rate limiting activo**
- ✅ **Logs configurados**
- ✅ **Backups recomendados**

---

## 📝 NOTAS FINALES

- ⚠️ **NUNCA** subas `.env` a Git
- ⚠️ **SIEMPRE** usa contraseñas fuertes (16+ caracteres)
- ⚠️ Cambia credenciales de pasarelas a **producción** (no test/sandbox)
- ⚠️ Haz **backups** de BD semanalmente
- ⚠️ Monitorea **logs** durante primeras 24h después del deploy

---

**¿Dudas?** Consulta las guías detalladas o ejecuta:
```bash
php artisan security:check
```

---

**¡Tu plataforma está blindada! 🛡️**
