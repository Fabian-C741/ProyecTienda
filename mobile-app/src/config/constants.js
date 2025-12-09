// API Configuration
export const API_URL = process.env.EXPO_PUBLIC_API_URL || 'http://localhost:8000/api/v1';
export const API_TIMEOUT = 10000;

// Storage Keys
export const STORAGE_KEYS = {
  TOKEN: 'userToken',
  USER: 'userData',
  CART: 'cartData',
};

// App Configuration
export const APP_CONFIG = {
  name: 'Mi Tienda',
  version: '1.0.0',
  currency: 'USD',
  currencySymbol: '$',
};

// Navigation
export const ROUTES = {
  // Auth
  LOGIN: 'Login',
  REGISTER: 'Register',
  
  // Main
  HOME: 'Home',
  PRODUCTS: 'Products',
  PRODUCT_DETAIL: 'ProductDetail',
  CART: 'Cart',
  CHECKOUT: 'Checkout',
  PROFILE: 'Profile',
  ORDERS: 'Orders',
  ORDER_DETAIL: 'OrderDetail',
};
