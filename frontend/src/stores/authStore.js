import { create } from 'zustand'
import { persist } from 'zustand/middleware'
import api from '../lib/axios'
import toast from 'react-hot-toast'

export const useAuthStore = create(
  persist(
    (set, get) => ({
      user: null,
      token: null,
      isAuthenticated: false,
      isLoading: false,

      login: async (credentials) => {
        set({ isLoading: true })
        try {
          const { data } = await api.post('/login', credentials)
          
          localStorage.setItem('auth_token', data.token)
          localStorage.setItem('user', JSON.stringify(data.user))
          
          set({
            user: data.user,
            token: data.token,
            isAuthenticated: true,
            isLoading: false,
          })
          
          toast.success('Sesión iniciada correctamente')
          return data
        } catch (error) {
          set({ isLoading: false })
          const message = error.response?.data?.message || 'Error al iniciar sesión'
          toast.error(message)
          throw error
        }
      },

      register: async (userData) => {
        set({ isLoading: true })
        try {
          const { data } = await api.post('/register', userData)
          
          localStorage.setItem('auth_token', data.token)
          localStorage.setItem('user', JSON.stringify(data.user))
          
          set({
            user: data.user,
            token: data.token,
            isAuthenticated: true,
            isLoading: false,
          })
          
          toast.success('Registro exitoso')
          return data
        } catch (error) {
          set({ isLoading: false })
          const message = error.response?.data?.message || 'Error al registrarse'
          toast.error(message)
          throw error
        }
      },

      logout: async () => {
        try {
          await api.post('/logout')
        } catch (error) {
          console.error('Error al cerrar sesión:', error)
        } finally {
          localStorage.removeItem('auth_token')
          localStorage.removeItem('user')
          
          set({
            user: null,
            token: null,
            isAuthenticated: false,
          })
          
          toast.success('Sesión cerrada')
        }
      },

      updateProfile: async (profileData) => {
        try {
          const { data } = await api.put('/profile', profileData)
          
          set({ user: data.user })
          localStorage.setItem('user', JSON.stringify(data.user))
          
          toast.success('Perfil actualizado')
          return data
        } catch (error) {
          toast.error('Error al actualizar perfil')
          throw error
        }
      },

      checkAuth: async () => {
        const token = localStorage.getItem('auth_token')
        if (!token) {
          set({ isAuthenticated: false })
          return
        }

        try {
          const { data } = await api.get('/me')
          set({
            user: data.user,
            isAuthenticated: true,
          })
        } catch (error) {
          set({
            user: null,
            token: null,
            isAuthenticated: false,
          })
          localStorage.removeItem('auth_token')
          localStorage.removeItem('user')
        }
      },

      hasRole: (role) => {
        const { user } = get()
        return user?.roles?.some((r) => r.name === role) || false
      },
    }),
    {
      name: 'auth-storage',
      partialize: (state) => ({
        user: state.user,
        token: state.token,
        isAuthenticated: state.isAuthenticated,
      }),
    }
  )
)
