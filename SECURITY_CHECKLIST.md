# 🔒 CHECKLIST DE SEGURIDAD - ANTES DE PRODUCCIÓN

## ✅ FASE 1: CONFIGURACIÓN INICIAL

### Base de Datos
- [ ] Base de datos creada en Hostinger con nombre NO obvio
- [ ] Usuario de BD creado (NO "root", NO "admin")
- [ ] Contraseña de BD fuerte (16+ caracteres, incluye símbolos, números, mayúsculas)
- [ ] Permisos de usuario BD limitados SOLO a la base de datos específica
- [ ] Credenciales guardadas en gestor de contraseñas (LastPass, 1Password, Bitwarden)

### Archivo .env
- [ ] Archivo .env creado en servidor (NO subido a Git)
- [ ] APP_ENV=production (NO "local" o "development")
- [ ] APP_DEBUG=false (CRÍTICO)
- [ ] APP_KEY generada con `php artisan key:generate`
- [ ] Credenciales de BD correctas (DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD)
- [ ] FRONTEND_URL apunta a tu dominio real
- [ ] SANCTUM_STATEFUL_DOMAINS configurado con tu dominio

---

## ✅ FASE 2: SEGURIDAD DE ARCHIVOS

### Permisos de Archivos
```bash
# Ejecutar en SSH
chmod 644 .env
chmod 644 composer.json
chmod 755 storage
chmod 755 bootstrap/cache
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

- [ ] .env con permisos 644
- [ ] storage/ con permisos 775
- [ ] bootstrap/cache/ con permisos 775
- [ ] Otros archivos PHP con permisos 644
- [ ] Directorios con permisos 755

### Protección .htaccess
- [ ] .htaccess creado en raíz del backend
- [ ] .env protegido contra acceso web
- [ ] Listado de directorios deshabilitado (Options -Indexes)
- [ ] Archivos .blade.php protegidos

### Test de Protección
- [ ] Intentar acceder a https://tudominio.com/api/.env → Debe dar 403 Forbidden
- [ ] Intentar acceder a https://tudominio.com/api/storage → Debe dar 403 si no es público

---

## ✅ FASE 3: SSL Y HTTPS

### Certificado SSL
- [ ] SSL activado en Hostinger (gratuito con Let's Encrypt)
- [ ] Certificado válido y no expirado
- [ ] Dominio principal con SSL
- [ ] Subdominios con SSL (si aplica)

### Forzar HTTPS
- [ ] .htaccess redirige HTTP → HTTPS
- [ ] APP_URL usa https:// (no http://)
- [ ] FRONTEND_URL usa https://

### Test SSL
- [ ] Visitar https://tudominio.com → Candado verde visible
- [ ] Test en https://www.ssllabs.com/ssltest/ → Calificación A o superior
- [ ] No hay advertencias de "contenido mixto"

---

## ✅ FASE 4: AUTENTICACIÓN Y AUTORIZACIÓN

### Laravel Sanctum
- [ ] Sanctum instalado (`composer require laravel/sanctum`)
- [ ] Migración de sanctum ejecutada
- [ ] SANCTUM_STATEFUL_DOMAINS configurado
- [ ] SESSION_DRIVER=file o database
- [ ] SESSION_ENCRYPT=true

### Spatie Permissions
- [ ] Spatie instalado (`composer require spatie/laravel-permission`)
- [ ] Migración de permisos ejecutada
- [ ] Roles creados: super_admin, tenant_admin, customer
- [ ] Permisos asignados correctamente
- [ ] RolesAndPermissionsSeeder ejecutado

### Contraseñas
- [ ] PASSWORD_MIN_LENGTH=8 (mínimo)
- [ ] Validación de contraseña fuerte activada
- [ ] Bcrypt o Argon2 para hash de contraseñas
- [ ] Usuario admin inicial con contraseña FUERTE cambiada

---

## ✅ FASE 5: RATE LIMITING Y THROTTLING

### Configuración
- [ ] THROTTLE_REQUESTS=60 (requests por minuto)
- [ ] LOGIN_MAX_ATTEMPTS=5
- [ ] LOGIN_DECAY_MINUTES=15
- [ ] Middleware throttle aplicado en rutas API

### Test de Rate Limiting
```bash
# Ejecutar 100 requests rápidos
for i in {1..100}; do curl https://tudominio.com/api/v1/products; done
```
- [ ] Después de 60 requests se bloquea temporalmente (429 Too Many Requests)

---

## ✅ FASE 6: CORS Y HEADERS DE SEGURIDAD

### CORS
- [ ] Middleware Cors registrado en Kernel.php
- [ ] ALLOWED_ORIGINS solo incluye dominios de confianza
- [ ] No usar '*' en producción
- [ ] Access-Control-Allow-Credentials=true si es necesario

### Security Headers
- [ ] X-XSS-Protection: 1; mode=block
- [ ] X-Frame-Options: SAMEORIGIN
- [ ] X-Content-Type-Options: nosniff
- [ ] Strict-Transport-Security (HSTS) activo
- [ ] Content-Security-Policy configurado
- [ ] X-Powered-By removido

### Test Headers
```bash
curl -I https://tudominio.com/api/v1/products
```
- [ ] Headers de seguridad presentes en respuesta

---

## ✅ FASE 7: VALIDACIÓN DE ENTRADA

### Validación Laravel
- [ ] Form Requests creados para endpoints críticos
- [ ] Reglas de validación estrictas
- [ ] Mensajes de error claros pero NO reveladores

### Prevención de Inyecciones
- [ ] NUNCA usar DB::raw() con input de usuario
- [ ] Usar Eloquent o Query Builder con bindings
- [ ] Validar tipos de datos (integer, string, email, etc.)
- [ ] Escapar output en frontend

### Test de Validación
- [ ] Intentar enviar SQL en campos: `' OR 1=1--`
- [ ] Intentar XSS: `<script>alert('xss')</script>`
- [ ] Verificar que se rechaza con error 422

---

## ✅ FASE 8: PASARELAS DE PAGO

### Mercado Pago
- [ ] Credenciales de PRODUCCIÓN (no sandbox)
- [ ] MERCADOPAGO_PUBLIC_KEY usa APP_USR (no TEST)
- [ ] MERCADOPAGO_ACCESS_TOKEN es de producción
- [ ] Webhook configurado en panel de Mercado Pago
- [ ] MERCADOPAGO_WEBHOOK_SECRET configurado
- [ ] Validación de firma en webhook

### Stripe
- [ ] Credenciales pk_live y sk_live (no pk_test)
- [ ] Webhook secret (whsec_) configurado
- [ ] Validación de firma en webhook
- [ ] Modo test desactivado

### PayPal
- [ ] PAYPAL_MODE=live (no sandbox)
- [ ] Credenciales de producción
- [ ] IPN o webhook configurado
- [ ] URL de retorno es HTTPS

### Test de Pagos
- [ ] Hacer pago de prueba real (mínimo monto)
- [ ] Verificar que webhook se recibe correctamente
- [ ] Orden se marca como "paid" en base de datos
- [ ] Email de confirmación se envía

---

## ✅ FASE 9: EMAIL

### Configuración SMTP
- [ ] Servicio de email configurado (SendGrid, Mailgun, etc.)
- [ ] MAIL_HOST, MAIL_PORT, MAIL_USERNAME, MAIL_PASSWORD correctos
- [ ] MAIL_ENCRYPTION=tls
- [ ] MAIL_FROM_ADDRESS es tu dominio (no @gmail.com)

### Test de Email
- [ ] Enviar email de prueba: `php artisan tinker` → `Mail::raw('test', ...)`
- [ ] Email llega correctamente
- [ ] No va a spam
- [ ] Formato correcto

---

## ✅ FASE 10: LOGS Y MONITOREO

### Configuración de Logs
- [ ] LOG_LEVEL=error (no debug en producción)
- [ ] LOG_CHANNEL=daily
- [ ] LOG_RETENTION_DAYS configurado
- [ ] Logs NO expuestos públicamente

### Acceso a Logs
- [ ] Saber cómo acceder a storage/logs/laravel.log
- [ ] Configurar alertas de errores críticos
- [ ] Monitoreo de espacio en disco

### Test
- [ ] Forzar un error y verificar que se loguea
- [ ] Verificar que errores NO se muestran al usuario (APP_DEBUG=false)

---

## ✅ FASE 11: BACKUP

### Backup de Base de Datos
- [ ] Configurar backup automático (cron job)
- [ ] Frecuencia: diaria o semanal
- [ ] Almacenar backups FUERA del servidor
- [ ] Probar restauración de backup

### Backup de Archivos
- [ ] Backup de .env
- [ ] Backup de storage/app (imágenes, uploads)
- [ ] Backup de código (Git es suficiente)

### Plan de Recuperación
- [ ] Documentar proceso de restauración
- [ ] Tener acceso a backups recientes
- [ ] Probar restauración al menos una vez

---

## ✅ FASE 12: TENANT ISOLATION

### Multi-tenant
- [ ] Middleware ValidateTenant registrado
- [ ] Cada query incluye tenant_id
- [ ] Scopes globales en modelos para tenant
- [ ] Usuario NO puede acceder a datos de otro tenant

### Test de Isolation
- [ ] Crear 2 tenants
- [ ] Crear productos en cada uno
- [ ] Verificar que tenant A no ve productos de tenant B
- [ ] Intentar acceder con tenant_id incorrecto → Error 404/403

---

## ✅ FASE 13: MIGRACIONES Y SEEDERS

### Migraciones
- [ ] Todas las migraciones ejecutadas: `php artisan migrate`
- [ ] Sin errores en migraciones
- [ ] Tablas creadas correctamente

### Seeders
- [ ] RolesAndPermissionsSeeder ejecutado
- [ ] Usuario super_admin creado
- [ ] Roles asignados correctamente

### Verificación
```sql
SELECT * FROM roles;
SELECT * FROM permissions;
SELECT * FROM users WHERE email = 'admin@tudominio.com';
```

---

## ✅ FASE 14: FRONTEND

### Configuración
- [ ] VITE_API_URL apunta a tu API de producción
- [ ] Build de producción creado: `npm run build`
- [ ] Assets versionados (hash en nombres de archivo)
- [ ] Service Worker configurado (si aplica)

### Test
- [ ] Todas las páginas cargan
- [ ] Login funciona
- [ ] Agregar al carrito funciona
- [ ] Checkout funciona
- [ ] Sin errores en consola del navegador

---

## ✅ FASE 15: DEPLOYMENT

### Git Deployment
- [ ] Repositorio conectado en Hostinger
- [ ] Branch de producción configurado
- [ ] Git pull automático (webhook o cron)
- [ ] Post-deployment script configurado

### Verificación Post-Deploy
- [ ] `composer install --no-dev --optimize-autoloader`
- [ ] `php artisan config:cache`
- [ ] `php artisan route:cache`
- [ ] `php artisan view:cache`
- [ ] Permisos de archivos correctos después de deploy

---

## ✅ FASE 16: TESTING FINAL

### Tests de Seguridad
- [ ] Escaneo de vulnerabilidades: https://observatory.mozilla.org/
- [ ] Test de headers: https://securityheaders.com/
- [ ] Test de SSL: https://www.ssllabs.com/ssltest/

### Tests Funcionales
- [ ] Usuario puede registrarse
- [ ] Usuario puede hacer login
- [ ] Usuario puede ver productos
- [ ] Usuario puede agregar al carrito
- [ ] Usuario puede hacer checkout
- [ ] Pago se procesa correctamente
- [ ] Email de confirmación llega

### Tests de Carga
- [ ] Probar con 10+ usuarios simultáneos
- [ ] Verificar tiempos de respuesta < 2 segundos
- [ ] Sin errores 500 bajo carga

---

## ✅ FASE 17: DOCUMENTACIÓN

### Documentos Creados
- [ ] README.md actualizado
- [ ] SEGURIDAD_Y_BD.md disponible
- [ ] API documentada (endpoints, parámetros, respuestas)
- [ ] Proceso de deployment documentado

### Credenciales Seguras
- [ ] Lista de todas las credenciales
- [ ] Guardadas en gestor de contraseñas
- [ ] Acceso limitado solo a personas necesarias

---

## ✅ FASE 18: GO LIVE

### Pre-Launch
- [ ] Todos los checks anteriores completados
- [ ] Backup completo realizado
- [ ] Equipo notificado
- [ ] Plan de rollback preparado

### Launch
- [ ] DNS apuntando correctamente
- [ ] SSL activo
- [ ] Aplicación accesible
- [ ] Sin errores 500

### Post-Launch
- [ ] Monitorear logs durante primeras 24 horas
- [ ] Verificar emails llegan
- [ ] Verificar pagos funcionan
- [ ] Soporte listo para usuarios

---

## 🚨 CHECKLIST DE EMERGENCIA

### Si algo sale mal:

1. **Error 500:**
   - [ ] Revisar storage/logs/laravel.log
   - [ ] Verificar .env
   - [ ] Verificar permisos de archivos

2. **No conecta a BD:**
   - [ ] Verificar credenciales en .env
   - [ ] Verificar que BD existe
   - [ ] Verificar permisos de usuario BD

3. **Pagos no funcionan:**
   - [ ] Verificar credenciales de pasarela
   - [ ] Verificar logs de webhook
   - [ ] Verificar que webhook URL es accesible

4. **Brecha de seguridad:**
   - [ ] Cambiar inmediatamente APP_KEY
   - [ ] Cambiar contraseñas de BD
   - [ ] Revocar tokens de API
   - [ ] Revisar logs de acceso
   - [ ] Restaurar backup si es necesario

---

## 📊 SCORE FINAL

**Total checks:** 200+
**Completados:** _____ / 200+
**Porcentaje:** _____ %

**Mínimo para producción:** 95% (190+ checks)

---

## ✅ APROBACIÓN FINAL

- [ ] **Desarrollador:** Revisó y completó todos los checks
- [ ] **Cliente/PM:** Aprobó go-live
- [ ] **Fecha de deployment:** __________
- [ ] **Responsable:** __________

---

**Firma digital (commit hash):** _________________________

