# Tienda Online Multi-Tenant

Plataforma de e-commerce multi-tenant con panel de super administrador, gestión de inquilinos y marketplace público.

## 🚀 Características

- **Multi-Tenant**: Múltiples tiendas en una sola plataforma
- **Roles y Permisos**: SuperAdmin, TenantAdmin, Customer, Guest
- **Catálogo Público**: Navegación sin login requerido
- **Pasarelas de Pago**: Mercado Pago, Stripe, PayPal
- **Responsive**: Diseño adaptable a todos los dispositivos
- **API REST**: Backend con Laravel + Frontend con React

## 📁 Estructura del Proyecto

```
├── backend/          # Laravel 10.x API
├── frontend/         # React + Vite SPA
├── docs/             # Documentación
└── deployment/       # Scripts de despliegue
```

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

## 🌐 Despliegue en Hostinger

Ver documentación en `deployment/HOSTINGER.md`

## 📝 Licencia

Propietario - Todos los derechos reservados
