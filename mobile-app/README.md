# 📱 TiendaApp - Mobile Application

Aplicación móvil React Native (Expo) para la tienda online multi-tenant.

## 🚀 Características

- ✅ Autenticación con JWT
- 🛍️ Catálogo de productos con búsqueda y filtros
- 🛒 Carrito de compras interactivo
- 📦 Historial de órdenes
- 👤 Perfil de usuario
- 💳 Integración con pasarelas de pago
- 🌐 Consumo de API REST

## 📋 Requisitos

- Node.js 16+
- npm o yarn
- Expo CLI
- Expo Go App (para testing en dispositivo)

## 🔧 Instalación

```bash
# Instalar dependencias
cd mobile-app
npm install

# Iniciar servidor de desarrollo
npm start

# Ejecutar en Android
npm run android

# Ejecutar en iOS
npm run ios

# Ejecutar en navegador
npm run web
```

## 📱 Estructura del Proyecto

```
mobile-app/
├── App.js                    # Componente raíz y navegación
├── app.json                  # Configuración de Expo
├── package.json              # Dependencias
├── babel.config.js           # Configuración de Babel
└── src/
    ├── contexts/
    │   ├── AuthContext.js    # Contexto de autenticación
    │   └── CartContext.js    # Contexto del carrito
    ├── services/
    │   ├── api.js            # Cliente Axios configurado
    │   └── index.js          # Servicios de API (auth, products, cart, orders)
    └── screens/
        ├── LoginScreen.js    # Pantalla de login
        ├── HomeScreen.js     # Catálogo de productos
        ├── CartScreen.js     # Carrito de compras
        ├── ProfileScreen.js  # Perfil de usuario
        └── OrdersScreen.js   # Historial de órdenes
```

## 🔐 Autenticación

La app usa JWT tokens almacenados en AsyncStorage:

```javascript
import { useAuth } from './src/contexts/AuthContext';

function MyComponent() {
  const { user, signIn, signOut } = useAuth();
  
  // Login
  await signIn('cliente@tienda.com', 'password123');
  
  // Logout
  await signOut();
}
```

## 🛒 Carrito de Compras

Gestión del carrito con Context API:

```javascript
import { useCart } from './src/contexts/CartContext';

function ProductScreen() {
  const { addItem, cart, cartCount } = useCart();
  
  // Agregar producto
  await addItem(productId, quantity);
  
  // Ver total
  console.log(cart.total);
}
```

## 🌐 API Configuration

La URL de la API se configura en `app.json`:

```json
{
  "expo": {
    "extra": {
      "apiUrl": "https://ingreso-tienda.kcrsf.com/api/v1"
    }
  }
}
```

## 📦 Dependencias Principales

- **react**: 18.2.0
- **react-native**: 0.74.1
- **expo**: ~51.0.0
- **@react-navigation/native**: ^6.1.7
- **axios**: ^1.6.0
- **@react-native-async-storage/async-storage**: 1.23.1

## 🎨 Navegación

La app usa React Navigation con Tab Navigator:

- 🏠 **Home**: Catálogo de productos
- 🛒 **Cart**: Carrito de compras
- 📦 **Orders**: Historial de órdenes
- 👤 **Profile**: Perfil de usuario

## 🧪 Testing

```bash
# Testing en Expo Go
npm start
# Escanear QR con Expo Go app
```

## 📲 Build para Producción

```bash
# Build Android APK
eas build --platform android

# Build iOS IPA
eas build --platform ios

# Build ambos
eas build --platform all
```

## 🔗 Enlaces

- **API**: https://ingreso-tienda.kcrsf.com/api/v1
- **Admin Panel**: https://ingreso-tienda.kcrsf.com/admin
- **GitHub**: https://github.com/Fabian-C741/ProyecTienda
