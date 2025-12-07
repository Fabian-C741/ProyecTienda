import api from './api';

export const authService = {
  login: async (email, password) => {
    const response = await api.post('/login', { email, password });
    return response.data;
  },

  register: async (userData) => {
    const response = await api.post('/register', userData);
    return response.data;
  },

  logout: async () => {
    const response = await api.post('/logout');
    return response.data;
  },

  me: async () => {
    const response = await api.get('/me');
    return response.data;
  },
};

export const productService = {
  getProducts: async (params = {}) => {
    const response = await api.get('/products', { params });
    return response.data;
  },

  getProduct: async (id) => {
    const response = await api.get(`/products/${id}`);
    return response.data;
  },

  getCategories: async () => {
    const response = await api.get('/categories');
    return response.data;
  },
};

export const cartService = {
  getCart: async () => {
    const response = await api.get('/cart');
    return response.data;
  },

  addToCart: async (productId, quantity = 1) => {
    const response = await api.post('/cart/add', {
      product_id: productId,
      quantity,
    });
    return response.data;
  },

  updateCart: async (productId, quantity) => {
    const response = await api.put('/cart/update', {
      product_id: productId,
      quantity,
    });
    return response.data;
  },

  removeFromCart: async (productId) => {
    const response = await api.delete('/cart/remove', {
      data: { product_id: productId },
    });
    return response.data;
  },

  clearCart: async () => {
    const response = await api.delete('/cart/clear');
    return response.data;
  },
};

export const orderService = {
  createOrder: async (orderData) => {
    const response = await api.post('/orders', orderData);
    return response.data;
  },

  getOrders: async () => {
    const response = await api.get('/orders');
    return response.data;
  },

  getOrder: async (id) => {
    const response = await api.get(`/orders/${id}`);
    return response.data;
  },
};

export const paymentService = {
  initiatePayment: async (orderId, paymentData) => {
    const response = await api.post(`/payments/${orderId}/initiate`, paymentData);
    return response.data;
  },
};
