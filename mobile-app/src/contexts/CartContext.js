import React, { createContext, useState, useContext, useEffect } from 'react';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { cartService } from '../services';

const CartContext = createContext({});

export const CartProvider = ({ children }) => {
  const [cart, setCart] = useState({ items: [], total: 0, subtotal: 0 });
  const [loading, setLoading] = useState(false);

  async function loadCart() {
    try {
      setLoading(true);
      const response = await cartService.getCart();
      setCart(response.cart || { items: [], total: 0, subtotal: 0 });
    } catch (error) {
      console.error('Error loading cart:', error);
    } finally {
      setLoading(false);
    }
  }

  async function addItem(productId, quantity = 1) {
    try {
      const response = await cartService.addToCart(productId, quantity);
      setCart(response.cart);
      return { success: true };
    } catch (error) {
      return {
        success: false,
        error: error.response?.data?.message || 'Error al agregar al carrito',
      };
    }
  }

  async function updateItem(productId, quantity) {
    try {
      const response = await cartService.updateCart(productId, quantity);
      setCart(response.cart);
      return { success: true };
    } catch (error) {
      return {
        success: false,
        error: error.response?.data?.message || 'Error al actualizar carrito',
      };
    }
  }

  async function removeItem(productId) {
    try {
      const response = await cartService.removeFromCart(productId);
      setCart(response.cart);
      return { success: true };
    } catch (error) {
      return {
        success: false,
        error: error.response?.data?.message || 'Error al eliminar del carrito',
      };
    }
  }

  async function clearCart() {
    try {
      await cartService.clearCart();
      setCart({ items: [], total: 0, subtotal: 0 });
      return { success: true };
    } catch (error) {
      return {
        success: false,
        error: error.response?.data?.message || 'Error al limpiar carrito',
      };
    }
  }

  const cartCount = cart.items.reduce((sum, item) => sum + item.quantity, 0);

  return (
    <CartContext.Provider
      value={{
        cart,
        loading,
        cartCount,
        loadCart,
        addItem,
        updateItem,
        removeItem,
        clearCart,
      }}
    >
      {children}
    </CartContext.Provider>
  );
};

export function useCart() {
  const context = useContext(CartContext);
  if (!context) {
    throw new Error('useCart must be used within a CartProvider');
  }
  return context;
}
