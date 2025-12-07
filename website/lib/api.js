import axios from 'axios';

const API_URL = process.env.NEXT_PUBLIC_API_URL || 'https://ingreso-tienda.kcrsf.com/api/v1';

const api = axios.create({
  baseURL: API_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

// Interceptor para agregar token
api.interceptors.request.use(
  (config) => {
    if (typeof window !== 'undefined') {
      const token = localStorage.getItem('token');
      if (token) {
        config.headers.Authorization = `Bearer ${token}`;
      }
    }
    return config;
  },
  (error) => Promise.reject(error)
);

// Interceptor para manejar errores
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401 && typeof window !== 'undefined') {
      localStorage.removeItem('token');
      localStorage.removeItem('user');
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);

export default api;

// Auth
export const authAPI = {
  login: (email, password) => api.post('/login', { email, password }),
  register: (userData) => api.post('/register', userData),
  logout: () => api.post('/logout'),
  me: () => api.get('/me'),
};

// Products
export const productsAPI = {
  getAll: (params) => api.get('/products', { params }),
  getById: (id) => api.get(`/products/${id}`),
  search: (query) => api.get('/products/search', { params: { q: query } }),
};

// Categories
export const categoriesAPI = {
  getAll: () => api.get('/categories'),
  getById: (id) => api.get(`/categories/${id}`),
};

// Cart
export const cartAPI = {
  get: () => api.get('/cart'),
  add: (productId, quantity) => api.post('/cart/add', { product_id: productId, quantity }),
  update: (productId, quantity) => api.put('/cart/update', { product_id: productId, quantity }),
  remove: (productId) => api.delete('/cart/remove', { data: { product_id: productId } }),
  clear: () => api.delete('/cart/clear'),
};

// Orders
export const ordersAPI = {
  create: (orderData) => api.post('/orders', orderData),
  getAll: () => api.get('/orders'),
  getById: (id) => api.get(`/orders/${id}`),
};

// Payments
export const paymentsAPI = {
  initiate: (orderId, paymentData) => api.post(`/payments/${orderId}/initiate`, paymentData),
};

// Reviews
export const reviewsAPI = {
  getByProduct: (productId) => api.get(`/products/${productId}/reviews`),
  create: (productId, reviewData) => api.post(`/products/${productId}/reviews`, reviewData),
};
