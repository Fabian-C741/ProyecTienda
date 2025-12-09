# 🛍️ Tienda Online Multi-Tenant

Sistema de e-commerce multi-tenant desarrollado con Laravel y React Native.

## 📦 Estructura del Proyecto

```
Tienda_online_multiplataformas/
├── backend/              # API Laravel + Panel Admin
├── mobile-app/          # App móvil React Native (Expo)
└── website/             # Website Next.js
```

## 🛠️ Stack Tecnológico

**Backend:**
- Laravel 10.x
- MySQL 8.0+
- PHP 8.2+

**Frontend Mobile:**
- React Native
- Expo
- TypeScript

**Frontend Web:**
- Next.js 14
- React 18
- TailwindCSS

## 📄 Licencia

Este proyecto es privado y confidencial.

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
