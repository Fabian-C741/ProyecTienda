import { useEffect, useState } from 'react'
import { Link } from 'react-router-dom'
import { ArrowRight, Star } from 'lucide-react'
import api from '../lib/axios'
import ProductCard from '../components/ProductCard'

export default function Home() {
  const [featuredProducts, setFeaturedProducts] = useState([])
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    fetchFeaturedProducts()
  }, [])

  const fetchFeaturedProducts = async () => {
    try {
      const { data } = await api.get('/products/featured')
      setFeaturedProducts(data.products)
    } catch (error) {
      console.error('Error al cargar productos destacados:', error)
    } finally {
      setLoading(false)
    }
  }

  return (
    <div>
      {/* Hero Section */}
      <section className="bg-gradient-to-r from-primary-600 to-primary-800 text-white py-20">
        <div className="container mx-auto px-4">
          <div className="max-w-3xl">
            <h1 className="text-5xl font-bold mb-4">
              Bienvenido a TiendaOnline
            </h1>
            <p className="text-xl mb-8">
              Descubre productos increíbles de vendedores de confianza. 
              Tu marketplace favorito para comprar y vender.
            </p>
            <div className="flex space-x-4">
              <Link to="/products" className="bg-white text-primary-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                Ver Productos
              </Link>
              <Link to="/register" className="border-2 border-white px-6 py-3 rounded-lg font-semibold hover:bg-white hover:text-primary-600 transition">
                Crear Cuenta
              </Link>
            </div>
          </div>
        </div>
      </section>

      {/* Features */}
      <section className="py-16 bg-white">
        <div className="container mx-auto px-4">
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div className="text-center">
              <div className="bg-primary-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                <Star className="h-8 w-8 text-primary-600" />
              </div>
              <h3 className="text-xl font-semibold mb-2">Productos de Calidad</h3>
              <p className="text-gray-600">
                Vendedores verificados con productos de la mejor calidad
              </p>
            </div>

            <div className="text-center">
              <div className="bg-primary-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                <Star className="h-8 w-8 text-primary-600" />
              </div>
              <h3 className="text-xl font-semibold mb-2">Envío Rápido</h3>
              <p className="text-gray-600">
                Recibe tus productos en tiempo récord
              </p>
            </div>

            <div className="text-center">
              <div className="bg-primary-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                <Star className="h-8 w-8 text-primary-600" />
              </div>
              <h3 className="text-xl font-semibold mb-2">Pago Seguro</h3>
              <p className="text-gray-600">
                Múltiples métodos de pago seguros y confiables
              </p>
            </div>
          </div>
        </div>
      </section>

      {/* Featured Products */}
      <section className="py-16 bg-gray-50">
        <div className="container mx-auto px-4">
          <div className="flex justify-between items-center mb-8">
            <h2 className="text-3xl font-bold">Productos Destacados</h2>
            <Link 
              to="/products" 
              className="flex items-center text-primary-600 hover:text-primary-700 font-semibold"
            >
              Ver todos <ArrowRight className="ml-2 h-5 w-5" />
            </Link>
          </div>

          {loading ? (
            <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
              {[...Array(8)].map((_, i) => (
                <div key={i} className="card animate-pulse">
                  <div className="bg-gray-300 h-64"></div>
                  <div className="p-4 space-y-3">
                    <div className="bg-gray-300 h-4 rounded"></div>
                    <div className="bg-gray-300 h-4 w-2/3 rounded"></div>
                  </div>
                </div>
              ))}
            </div>
          ) : (
            <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
              {featuredProducts.map((product) => (
                <ProductCard key={product.id} product={product} />
              ))}
            </div>
          )}
        </div>
      </section>

      {/* CTA Section */}
      <section className="py-16 bg-primary-600 text-white">
        <div className="container mx-auto px-4 text-center">
          <h2 className="text-3xl font-bold mb-4">¿Quieres vender en nuestra plataforma?</h2>
          <p className="text-xl mb-8 max-w-2xl mx-auto">
            Únete a miles de vendedores exitosos y comienza a vender tus productos hoy mismo
          </p>
          <Link 
            to="/register" 
            className="bg-white text-primary-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition inline-block"
          >
            Comienza a Vender
          </Link>
        </div>
      </section>
    </div>
  )
}
