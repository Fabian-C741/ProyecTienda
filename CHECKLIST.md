# Checklist de Despliegue

## ✅ Pre-Despliegue (En tu PC)

- [ ] Base de datos creada en Hostinger
- [ ] Subdominio configurado en Hostinger
- [ ] Anotadas credenciales de BD (nombre, usuario, contraseña)
- [ ] Anotada URL del subdominio

## ✅ Configuración Local

### Backend
- [ ] Editar `backend\.env`:
  - [ ] `APP_ENV=production`
  - [ ] `APP_DEBUG=false`
  - [ ] `APP_URL=https://tu-subdominio.com`
  - [ ] Credenciales correctas de BD
- [ ] Ejecutar `php artisan key:generate`
- [ ] Ejecutar `composer install --optimize-autoloader --no-dev`

### Frontend
- [ ] Crear `frontend\.env.production`
- [ ] Configurar `VITE_API_URL=https://tu-subdominio.com/api/v1`
- [ ] Ejecutar `npm run build`
- [ ] Verificar que existe carpeta `frontend\dist\`

## ✅ Subir Archivos

### Frontend (a public_html/)
- [ ] Subir contenido de `frontend\dist\` a `public_html/`
- [ ] Verificar que `index.html` está en raíz
- [ ] Verificar que existe carpeta `assets/`
- [ ] Subir `.htaccess` del frontend

### Backend (a public_html/api/)
- [ ] Crear carpeta `public_html/api/`
- [ ] Subir todo el contenido de `backend/` a `public_html/api/`
- [ ] Verificar que `.env` está presente
- [ ] Verificar que `.htaccess` está en `public_html/api/public/`

## ✅ Configuración en Servidor

### Permisos
- [ ] `chmod 755 public_html/api/storage`
- [ ] `chmod 755 public_html/api/bootstrap/cache`

### Base de Datos
- [ ] Ejecutar `php artisan migrate --force`
- [ ] Ejecutar `php artisan db:seed --force`
- [ ] Verificar que las tablas se crearon

### Archivos .htaccess
- [ ] `.htaccess` en `public_html/` (para React)
- [ ] `.htaccess` en `public_html/api/public/` (para Laravel)

## ✅ SSL/HTTPS
- [ ] SSL activado en Hostinger
- [ ] Forzar HTTPS configurado
- [ ] Certificado válido

## ✅ Verificación Final

### Frontend
- [ ] `https://tu-subdominio.com` carga correctamente
- [ ] Sin errores en consola del navegador
- [ ] Rutas de React funcionan (ej: /products, /login)

### API
- [ ] `https://tu-subdominio.com/api/v1/products` devuelve JSON
- [ ] Sin error 500 o 404

### Funcionalidad
- [ ] Login funciona (`admin@tienda.com` / `password`)
- [ ] Se pueden ver productos
- [ ] Carrito funciona
- [ ] Registro de usuario funciona

## ✅ Configuración Opcional (Post-Despliegue)

### Email
- [ ] Configurar SMTP (SendGrid, Mailgun, etc.)
- [ ] Probar envío de emails

### Pasarelas de Pago
- [ ] Configurar Mercado Pago (credenciales de producción)
- [ ] Configurar Stripe (credenciales de producción)
- [ ] Configurar PayPal (modo live)
- [ ] Configurar webhooks

### Seguridad
- [ ] Cambiar contraseña del super admin
- [ ] Deshabilitar registro público si no se necesita
- [ ] Revisar permisos de archivos
- [ ] Configurar backup automático de BD

### Optimización
- [ ] Activar compresión GZIP
- [ ] Configurar cache del navegador
- [ ] Optimizar imágenes
- [ ] Configurar CDN (opcional)

## 🆘 Si algo falla:

1. **Revisar logs:**
   - `public_html/api/storage/logs/laravel.log`

2. **Verificar configuración:**
   - `.env` tiene credenciales correctas
   - `.htaccess` están en sus lugares
   - Permisos de carpetas correctos

3. **Comandos útiles (SSH):**
   ```bash
   cd public_html/api
   php artisan config:clear
   php artisan cache:clear
   tail -f storage/logs/laravel.log
   ```

## 📊 Información de Contacto BD

**Anotar aquí para referencia:**

- Host: _______________
- Base de datos: _______________
- Usuario: _______________
- Contraseña: _______________
- Puerto: 3306

## 🌐 URLs del Proyecto

- Frontend: _______________
- API: _______________/api/v1
- Admin: _______________/admin
