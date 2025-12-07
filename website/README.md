# 🌐 TiendaOnline Website - Next.js E-Commerce

Sitio web público del e-commerce construido con Next.js 14, React 18 y Tailwind CSS.

## 🚀 Características

- ✅ Server-Side Rendering (SSR) para SEO optimizado
- 🛍️ Catálogo de productos con búsqueda y filtros en tiempo real
- 🛒 Carrito de compras persistente
- 👤 Autenticación de usuarios
- 💳 Integración con checkout
- 📱 Diseño responsive y mobile-first
- 🎨 UI moderna con Tailwind CSS
- ⚡ Optimización de imágenes con Next.js Image

## 📋 Requisitos

- Node.js 18+
- npm o yarn

## 🔧 Instalación

```bash
# Instalar dependencias
cd website
npm install

# Desarrollo
npm run dev

# Build para producción
npm run build

# Iniciar en producción
npm start
```

## 📁 Estructura del Proyecto

```
website/
├── pages/
│   ├── _app.js              # App wrapper
│   ├── _document.js         # Document wrapper
│   ├── index.js             # Homepage con catálogo
│   ├── login.js             # Página de login
│   ├── cart.js              # Carrito de compras
│   └── products/
│       └── [id].js          # Detalle de producto (dynamic route)
├── lib/
│   └── api.js               # Cliente API y servicios
├── styles/
│   └── globals.css          # Estilos globales y Tailwind
├── next.config.js           # Configuración de Next.js
├── tailwind.config.js       # Configuración de Tailwind
└── package.json             # Dependencias
```

## 🎨 Páginas Principales

### Homepage (`/`)
- **Características:**
  - Hero section con búsqueda
  - Filtros por categoría
  - Grid de productos responsivo
  - Footer con enlaces

### Detalle de Producto (`/products/[id]`)
- **Características:**
  - Imágenes en alta resolución
  - Información completa del producto
  - Selector de cantidad
  - Rating y reviews
  - Agregar al carrito
  - Breadcrumbs de navegación

### Carrito (`/cart`)
- **Características:**
  - Lista de productos agregados
  - Control de cantidad (+/-)
  - Cálculo de subtotal, envío e impuestos
  - Botón de checkout
  - Resumen del pedido
  - Eliminar productos

### Login (`/login`)
- **Características:**
  - Formulario de autenticación
  - Validación de campos
  - Manejo de errores
  - Cuentas de prueba mostradas
  - Redirección después de login

## 🔐 Autenticación

El website usa JWT tokens almacenados en localStorage:

```javascript
// Login
const response = await authAPI.login(email, password);
localStorage.setItem('token', response.data.token);
localStorage.setItem('user', JSON.stringify(response.data.user));

// Logout
localStorage.removeItem('token');
localStorage.removeItem('user');
```

## 🌐 API Configuration

La URL de la API se configura en `next.config.js`:

```javascript
module.exports = {
  env: {
    NEXT_PUBLIC_API_URL: 'https://ingreso-tienda.kcrsf.com/api/v1',
  },
}
```

## 📦 Dependencias

- **next**: 14.2.0 - Framework React con SSR
- **react**: 18.3.1 - Librería UI
- **tailwindcss**: 3.4.3 - Framework CSS
- **axios**: 1.7.2 - Cliente HTTP
- **swr**: 2.2.5 - Data fetching
- **zustand**: 4.5.2 - State management

## 🎨 Diseño

### Colores
- **Primary**: #4F46E5 (Indigo)
- **Secondary**: #EC4899 (Pink)
- **Success**: #10B981 (Green)
- **Warning**: #F59E0B (Amber)
- **Error**: #EF4444 (Red)

### Componentes Reutilizables
```css
.btn-primary      /* Botón principal con gradiente */
.btn-secondary    /* Botón secundario */
.card             /* Card con sombra y hover */
.input            /* Input con focus ring */
```

## 🚀 Deploy

### Vercel (Recomendado)
```bash
# Conectar con GitHub y deploy automático
vercel
```

### Build Manual
```bash
npm run build
npm start
```

## 📱 Responsive Design

- **Mobile**: < 640px
- **Tablet**: 640px - 1024px
- **Desktop**: > 1024px

Breakpoints de Tailwind:
- `sm`: 640px
- `md`: 768px
- `lg`: 1024px
- `xl`: 1280px
- `2xl`: 1536px

## 🔗 Enlaces

- **Website**: http://localhost:3000 (desarrollo)
- **API**: https://ingreso-tienda.kcrsf.com/api/v1
- **Admin Panel**: https://ingreso-tienda.kcrsf.com/admin
- **GitHub**: https://github.com/Fabian-C741/ProyecTienda

## 🧪 Testing

```bash
# Desarrollo
npm run dev
# Abrir http://localhost:3000

# Build de prueba
npm run build
npm start
```

## 📊 Performance

- ⚡ Lighthouse Score: 90+
- 🎯 SEO Optimizado con SSR
- 📦 Code Splitting automático
- 🖼️ Lazy loading de imágenes
- 🗜️ Compresión de assets

## 🛠️ Próximas Mejoras

- [ ] Checkout completo
- [ ] Registro de usuarios
- [ ] Perfil de usuario
- [ ] Historial de órdenes
- [ ] Sistema de reviews
- [ ] Wishlist
- [ ] Comparador de productos
