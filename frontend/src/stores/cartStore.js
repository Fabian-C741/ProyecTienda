import { create } from 'zustand'
import { persist } from 'zustand/middleware'
import api from '../lib/axios'
import toast from 'react-hot-toast'

export const useCartStore = create(
  persist(
    (set, get) => ({
      cart: null,
      isLoading: false,

      fetchCart: async () => {
        set({ isLoading: true })
        try {
          const { data } = await api.get('/cart')
          set({ cart: data.cart, isLoading: false })
          return data
        } catch (error) {
          set({ isLoading: false })
          console.error('Error al obtener carrito:', error)
        }
      },

      addItem: async (productId, quantity = 1, variant = null) => {
        try {
          const { data } = await api.post('/cart/items', {
            product_id: productId,
            quantity,
            variant,
          })
          
          set({ cart: data.cart })
          toast.success('Producto agregado al carrito')
          return data
        } catch (error) {
          const message = error.response?.data?.message || 'Error al agregar producto'
          toast.error(message)
          throw error
        }
      },

      updateItem: async (itemId, quantity) => {
        try {
          const { data } = await api.put(`/cart/items/${itemId}`, { quantity })
          set({ cart: data.cart })
          return data
        } catch (error) {
          toast.error('Error al actualizar cantidad')
          throw error
        }
      },

      removeItem: async (itemId) => {
        try {
          const { data } = await api.delete(`/cart/items/${itemId}`)
          set({ cart: data.cart })
          toast.success('Producto eliminado')
          return data
        } catch (error) {
          toast.error('Error al eliminar producto')
          throw error
        }
      },

      clearCart: async () => {
        try {
          await api.delete('/cart/clear')
          set({ cart: null })
          toast.success('Carrito vaciado')
        } catch (error) {
          toast.error('Error al vaciar carrito')
          throw error
        }
      },

      getItemsCount: () => {
        const { cart } = get()
        return cart?.items?.reduce((sum, item) => sum + item.quantity, 0) || 0
      },

      getTotal: () => {
        const { cart } = get()
        return cart?.total || 0
      },
    }),
    {
      name: 'cart-storage',
    }
  )
)
