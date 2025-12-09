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
1. Visita https://ingreso-tienda.kcrsf.com
2. Espera 3 segundos
3. Aparecerá popup "¿Instalar aplicación?"
4. Toca "Instalar App"
5. La app se agregará a tu pantalla de inicio

**En iOS (Safari):**
1. Visita https://ingreso-tienda.kcrsf.com
2. Toca botón "Compartir" → "Añadir a pantalla de inicio"

## 🔒 Seguridad

La aplicación incluye protecciones contra:
- SQL Injection
- XSS (Cross-Site Scripting)
- CSRF (Cross-Site Request Forgery)
- Clickjacking
- MIME Sniffing

## 📝 Licencia

Propietario - Todos los derechos reservados
