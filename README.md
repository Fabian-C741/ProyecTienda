# 🛍️ Tienda Online Multi-Tenant - Sistema Completo

Sistema de e-commerce multi-tenant completo con backend Laravel y 3 frontends: Panel Admin Web, Mobile App y Website público.

## 📦 Estructura del Proyecto

```
Tienda_online_multiplataformas/
├── backend/              # API Laravel 10 + MySQL
├── mobile-app/          # App móvil React Native (Expo)
├── website/             # Website público Next.js 14
└── .docs/               # Documentación completa
```

## 🚀 Componentes del Sistema

### 1. 🔧 Backend API (Laravel 10)
**Ubicación:** `backend/`  
**URL Producción:** https://ingreso-tienda.kcrsf.com/api/v1

**Características:**
- ✅ API REST completa con Laravel 10.50.0
- 🔐 Autenticación JWT con Laravel Sanctum
- 👥 Sistema de roles y permisos (Spatie)
- 🛒 Gestión de productos, categorías y carrito
- 📦 Sistema de órdenes completo
- 💳 5 métodos de pago integrados (Mercado Pago, Stripe, PayPal, etc.)
- ⭐ Sistema de reviews con ratings
- 📧 Notificaciones por email
- 🖼️ Upload de imágenes con optimización
- ⚡ Rate limiting (120 req/min)

### 2. 📊 Panel Admin Web (Laravel Blade)
**Ubicación:** `backend/resources/views/admin/`  
**URL Producción:** https://ingreso-tienda.kcrsf.com/admin/dashboard

**Características:**
- Dashboard con estadísticas en tiempo real
- CRUD completo de productos con filtros
- Gestión de órdenes con actualización de estado
- Interfaz moderna con Tailwind CSS + Alpine.js
- Totalmente responsive

### 3. 📱 Mobile App (React Native + Expo)
**Ubicación:** `mobile-app/`  
**Plataformas:** iOS, Android, Web

**Características:**
- Autenticación con JWT
- Catálogo de productos con búsqueda
- Carrito de compras interactivo
- Historial de órdenes
- Perfil de usuario

### 4. 🌐 Website Público (Next.js)
**Ubicación:** `website/`

**Características:**
- Server-Side Rendering (SSR)
- Catálogo completo con filtros
- Detalle de productos
- Carrito de compras
- Diseño responsive

## 🛠️ Stack Tecnológico

### Frontend
- React 18
- Vite
- TailwindCSS
- Axios
- React Router
- Zustand (estado global)

### Backend
- PHP 8.2+
- Laravel 10.x
- MySQL 8.0+
- Laravel Sanctum (autenticación)
- Spatie Permissions (roles)

### Servicios Externos
- Mercado Pago SDK
- Stripe API
- PayPal REST API
- SendGrid/Mailgun (emails)

## 🔧 Instalación Local

### Prerrequisitos
- PHP 8.2+
- Composer
- Node.js 18+
- MySQL 8.0+

### Backend (Laravel)
```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

### Frontend (React)
```bash
cd frontend
npm install
npm run dev
```

## 🔒 Seguridad

La aplicación incluye protecciones contra:
- SQL Injection
- XSS (Cross-Site Scripting)
- CSRF (Cross-Site Request Forgery)
- Clickjacking
- MIME Sniffing

## 📝 Licencia

Propietario - Todos los derechos reservados
