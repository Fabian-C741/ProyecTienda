import { Link, useNavigate } from 'react-router-dom'
import { ShoppingCart, User, LogOut, Menu, Search } from 'lucide-react'
import { useState } from 'react'
import { useAuthStore } from '../stores/authStore'
import { useCartStore } from '../stores/cartStore'

export default function Header() {
  const { isAuthenticated, user, logout } = useAuthStore()
  const { getItemsCount } = useCartStore()
  const navigate = useNavigate()
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false)

  const cartCount = getItemsCount()

  const handleLogout = async () => {
    await logout()
    navigate('/login')
  }

  return (
    <header className="bg-white shadow-md sticky top-0 z-50">
      <div className="container mx-auto px-4">
        <div className="flex items-center justify-between h-16">
          {/* Logo */}
          <Link to="/" className="text-2xl font-bold text-primary-600">
            TiendaOnline
          </Link>

          {/* Search Bar - Desktop */}
          <div className="hidden md:flex flex-1 max-w-lg mx-8">
            <div className="relative w-full">
              <input
                type="text"
                placeholder="Buscar productos..."
                className="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
              />
              <Search className="absolute left-3 top-2.5 h-5 w-5 text-gray-400" />
            </div>
          </div>

          {/* Navigation */}
          <nav className="hidden md:flex items-center space-x-6">
            <Link to="/products" className="text-gray-700 hover:text-primary-600">
              Productos
            </Link>

            {/* Cart */}
            <Link to="/cart" className="relative">
              <ShoppingCart className="h-6 w-6 text-gray-700 hover:text-primary-600" />
              {cartCount > 0 && (
                <span className="absolute -top-2 -right-2 bg-primary-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                  {cartCount}
                </span>
              )}
            </Link>

            {/* User Menu */}
            {isAuthenticated ? (
              <div className="relative group">
                <button className="flex items-center space-x-2 text-gray-700 hover:text-primary-600">
                  <User className="h-6 w-6" />
                  <span>{user?.name}</span>
                </button>
                
                <div className="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 hidden group-hover:block">
                  <Link to="/profile" className="block px-4 py-2 hover:bg-gray-100">
                    Mi Perfil
                  </Link>
                  <Link to="/orders" className="block px-4 py-2 hover:bg-gray-100">
                    Mis Pedidos
                  </Link>
                  {user?.roles?.some(r => ['super_admin', 'tenant_admin'].includes(r.name)) && (
                    <Link to="/admin" className="block px-4 py-2 hover:bg-gray-100">
                      Panel Admin
                    </Link>
                  )}
                  <button
                    onClick={handleLogout}
                    className="w-full text-left px-4 py-2 hover:bg-gray-100 flex items-center space-x-2"
                  >
                    <LogOut className="h-4 w-4" />
                    <span>Cerrar Sesión</span>
                  </button>
                </div>
              </div>
            ) : (
              <Link to="/login" className="btn btn-primary">
                Iniciar Sesión
              </Link>
            )}
          </nav>

          {/* Mobile Menu Button */}
          <button
            onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
            className="md:hidden"
          >
            <Menu className="h-6 w-6" />
          </button>
        </div>

        {/* Mobile Menu */}
        {mobileMenuOpen && (
          <div className="md:hidden py-4 space-y-4">
            <div className="relative">
              <input
                type="text"
                placeholder="Buscar..."
                className="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg"
              />
              <Search className="absolute left-3 top-2.5 h-5 w-5 text-gray-400" />
            </div>
            
            <Link to="/products" className="block py-2">Productos</Link>
            <Link to="/cart" className="block py-2">Carrito ({cartCount})</Link>
            
            {isAuthenticated ? (
              <>
                <Link to="/profile" className="block py-2">Mi Perfil</Link>
                <Link to="/orders" className="block py-2">Mis Pedidos</Link>
                <button onClick={handleLogout} className="block py-2 text-left w-full">
                  Cerrar Sesión
                </button>
              </>
            ) : (
              <Link to="/login" className="block py-2">Iniciar Sesión</Link>
            )}
          </div>
        )}
      </div>
    </header>
  )
}
