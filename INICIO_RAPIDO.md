# 🚀 Guía de Inicio Rápido

## Instalación Local

### 1. Requisitos Previos

- PHP 8.2 o superior
- Composer
- Node.js 18+ y npm
- MySQL 8.0+
- Git (opcional)

### 2. Configurar Backend (Laravel)

```powershell
# Navegar al directorio backend
cd backend

# Instalar dependencias de PHP
composer install

# Copiar archivo de configuración
Copy-Item .env.example .env

# Generar clave de aplicación
php artisan key:generate

# Configurar base de datos en .env
# Edita .env y configura:
# DB_DATABASE=tienda_multitenant
# DB_USERNAME=tu_usuario
# DB_PASSWORD=tu_password
```

**Crear la base de datos:**

```sql
CREATE DATABASE tienda_multitenant CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

**Ejecutar migraciones y seeders:**

```powershell
php artisan migrate
php artisan db:seed
```

**Instalar Spatie Permissions:**

```powershell
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

**Iniciar servidor:**

```powershell
php artisan serve
# Servidor disponible en: http://localhost:8000
```

### 3. Configurar Frontend (React + Vite)

```powershell
# Navegar al directorio frontend
cd ..\frontend

# Instalar dependencias
npm install

# Copiar configuración
Copy-Item .env.example .env

# Iniciar servidor de desarrollo
npm run dev
# Aplicación disponible en: http://localhost:5173
```

### 4. Credenciales por Defecto

**Super Admin:**
- Email: `admin@tienda.com`
- Password: `password`

## 🔧 Configuración de Pasarelas de Pago

### Mercado Pago

1. Crear cuenta en https://www.mercadopago.com
2. Ir a https://www.mercadopago.com/developers
3. Crear una aplicación
4. Obtener credenciales (Public Key y Access Token)
5. Agregar en `.env`:

```env
MERCADOPAGO_PUBLIC_KEY=tu_public_key
MERCADOPAGO_ACCESS_TOKEN=tu_access_token
```

### Stripe

1. Crear cuenta en https://stripe.com
2. Ir al Dashboard → Developers → API Keys
3. Obtener claves (Publishable key y Secret key)
4. Agregar en `.env`:

```env
STRIPE_PUBLIC_KEY=pk_test_xxx
STRIPE_SECRET_KEY=sk_test_xxx
```

### PayPal

1. Crear cuenta en https://developer.paypal.com
2. Crear app en "My Apps & Credentials"
3. Obtener Client ID y Secret
4. Agregar en `.env`:

```env
PAYPAL_MODE=sandbox
PAYPAL_CLIENT_ID=tu_client_id
PAYPAL_SECRET=tu_secret
```

## 📧 Configuración de Emails

### Desarrollo (Mailtrap)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=tu_username
MAIL_PASSWORD=tu_password
```

### Producción (SendGrid)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=SG.xxx
```

## 🏗️ Arquitectura Multi-Tenant

### Crear un Tenant (Tienda)

```php
// Via Tinker
php artisan tinker

$tenant = \App\Models\Tenant::create([
    'name' => 'Mi Tienda',
    'slug' => 'mi-tienda',
    'email' => 'contacto@mitienda.com',
    'is_active' => true,
]);

// Crear admin del tenant
$admin = \App\Models\User::create([
    'tenant_id' => $tenant->id,
    'name' => 'Admin Tienda',
    'email' => 'admin@mitienda.com',
    'password' => bcrypt('password'),
]);

$admin->assignRole('tenant_admin');
```

### Crear Producto

```php
$product = \App\Models\Product::create([
    'tenant_id' => 1,
    'category_id' => 1,
    'name' => 'Producto Ejemplo',
    'slug' => 'producto-ejemplo',
    'sku' => 'PROD-001',
    'description' => 'Descripción del producto',
    'price' => 99.99,
    'stock' => 100,
    'is_active' => true,
    'published_at' => now(),
]);
```

## 🧪 Testing de API

### Productos Públicos

```bash
curl http://localhost:8000/api/v1/products
```

### Login

```bash
curl -X POST http://localhost:8000/api/v1/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@tienda.com","password":"password"}'
```

### Crear Orden (con token)

```bash
curl -X POST http://localhost:8000/api/v1/orders \
  -H "Authorization: Bearer tu_token" \
  -H "Content-Type: application/json" \
  -d '{...}'
```

## 📦 Comandos Útiles

```powershell
# Backend
php artisan migrate:fresh --seed  # Reiniciar BD
php artisan cache:clear           # Limpiar cache
php artisan route:list            # Ver todas las rutas
php artisan queue:work            # Procesar cola (si usas jobs)

# Frontend
npm run build                     # Compilar para producción
npm run preview                   # Vista previa de build
```

## 🔍 Estructura del Proyecto

```
├── backend/                  # Laravel API
│   ├── app/
│   │   ├── Http/Controllers/
│   │   ├── Models/
│   │   └── ...
│   ├── database/
│   │   ├── migrations/
│   │   └── seeders/
│   └── routes/
│       └── api.php
│
├── frontend/                 # React SPA
│   ├── src/
│   │   ├── components/
│   │   ├── pages/
│   │   ├── stores/
│   │   └── lib/
│   └── package.json
│
└── deployment/              # Scripts de despliegue
    ├── HOSTINGER.md
    └── build.ps1
```

## 🎯 Próximos Pasos

1. ✅ Instalar dependencias backend y frontend
2. ✅ Configurar base de datos
3. ✅ Ejecutar migraciones y seeders
4. ✅ Probar login con credenciales por defecto
5. 🔄 Personalizar diseño y funcionalidades
6. 🔄 Configurar pasarelas de pago
7. 🔄 Agregar productos de prueba
8. 🔄 Deploy a producción (ver deployment/HOSTINGER.md)

## 🆘 Solución de Problemas

### Error: "Class not found"
```powershell
composer dump-autoload
```

### Error de migraciones
```powershell
php artisan migrate:fresh
```

### Puerto 8000 en uso
```powershell
php artisan serve --port=8001
```

### Node modules error
```powershell
Remove-Item -Recurse -Force node_modules
npm install
```

## 📚 Recursos

- [Documentación Laravel](https://laravel.com/docs)
- [Documentación React](https://react.dev)
- [Tailwind CSS](https://tailwindcss.com/docs)
- [Spatie Permissions](https://spatie.be/docs/laravel-permission)

## 💡 Tips

- Usa `php artisan tinker` para interactuar con la BD
- Revisa logs en `backend/storage/logs/laravel.log`
- Usa React DevTools para debugging del frontend
- Configura Xdebug para debugging de PHP

¡Listo para comenzar! 🎉
