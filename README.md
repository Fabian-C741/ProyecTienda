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

### 4. 🌐 Website Público (Laravel Blade + PWA)
**Ubicación:** `backend/resources/views/shop/`  

**Características:**
- ✅ Home con categorías y productos destacados
- ✅ Catálogo completo con filtros y búsqueda
- ✅ Detalle de productos con reviews
- ✅ Carrito de compras funcional
- 🎯 **PWA (Progressive Web App)**
  - Install prompt inteligente (aparece a los 3 segundos)
  - Cooldown de 24 horas si el usuario rechaza
  - Modo standalone (se abre como app nativa)
  - Manifest.json configurado
  - Diseño responsive
- Tailwind CSS + Alpine.js

**Documentación PWA:** Ver `PWA-COMPLETADO.md`

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

### Mobile App (React Native)
```bash
cd mobile-app
npm install
npm start
# Escanea el QR con Expo Go app
```

## 📱 PWA (Progressive Web App)

El sitio web público incluye capacidades de PWA:

### ✨ Características
- ✅ Install prompt inteligente
- ✅ Aparece automáticamente después de 3 segundos
- ✅ Respeta decisiones del usuario (cooldown 24h)
- ✅ Modo standalone (funciona como app nativa)
- ✅ Iconos configurados (SVG + PNG)
- ✅ Manifest.json completo

### 🧪 Cómo Probar
**En Android (Chrome):**
2. Espera 3 segundos
3. Aparecerá popup "¿Instalar aplicación?"
4. Toca "Instalar App"
5. La app se agregará a tu pantalla de inicio

**En iOS (Safari):**
2. Toca botón "Compartir" → "Añadir a pantalla de inicio"

### 📖 Documentación Completa
Ver archivo `PWA-COMPLETADO.md` para:
- Guía de personalización
- Crear iconos PNG personalizados
- Troubleshooting
- Personalizar comportamiento del popup

## 🚀 Desplegar Cambios

### Al Servidor de Producción
```bash
# Commit y push
git add .
git commit -m "Descripción del cambio"
git push origin main

# Desplegar en servidor
git pull origin main
```

### Credenciales del Servidor
- **Puerto:** 65002

### Panel de Administración
- **Password:** admin123

## 🔒 Seguridad

La aplicación incluye protecciones contra:
- SQL Injection
- XSS (Cross-Site Scripting)
- CSRF (Cross-Site Request Forgery)
- Clickjacking
- MIME Sniffing

## 📝 Licencia

Propietario - Todos los derechos reservados
