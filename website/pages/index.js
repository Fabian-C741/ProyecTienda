import { useState, useEffect } from 'react';
import Head from 'next/head';
import Link from 'next/link';
import Image from 'next/image';
import { productsAPI, categoriesAPI } from '../lib/api';

export default function Home() {
  const [products, setProducts] = useState([]);
  const [categories, setCategories] = useState([]);
  const [selectedCategory, setSelectedCategory] = useState(null);
  const [loading, setLoading] = useState(true);
  const [search, setSearch] = useState('');

  useEffect(() => {
    loadData();
  }, []);

  async function loadData() {
    try {
      setLoading(true);
      const [productsRes, categoriesRes] = await Promise.all([
        productsAPI.getAll(),
        categoriesAPI.getAll(),
      ]);
      setProducts(productsRes.data.products || []);
      setCategories(categoriesRes.data.categories || []);
    } catch (error) {
      console.error('Error loading data:', error);
    } finally {
      setLoading(false);
    }
  }

  const filteredProducts = products.filter((product) => {
    const matchesSearch = product.name.toLowerCase().includes(search.toLowerCase());
    const matchesCategory = selectedCategory ? product.category_id === selectedCategory : true;
    return matchesSearch && matchesCategory && product.active;
  });

  return (
    <>
      <Head>
        <title>TiendaOnline - Compra lo mejor</title>
      </Head>

      <div className="min-h-screen bg-gray-50">
        {/* Header */}
        <header className="bg-gradient-to-r from-primary-600 to-purple-600 text-white">
          <div className="container mx-auto px-4 py-6">
            <div className="flex items-center justify-between">
              <Link href="/" className="text-3xl font-bold">
                🛍️ TiendaOnline
              </Link>
              <div className="flex items-center space-x-6">
                <Link href="/cart" className="relative hover:text-primary-100 transition">
                  <span className="text-2xl">🛒</span>
                </Link>
                <Link href="/login" className="bg-white text-primary-600 px-4 py-2 rounded-lg font-semibold hover:bg-primary-50 transition">
                  Iniciar Sesión
                </Link>
              </div>
            </div>
          </div>
        </header>

        {/* Hero Section */}
        <section className="bg-gradient-to-r from-primary-500 to-purple-500 text-white py-20">
          <div className="container mx-auto px-4 text-center">
            <h1 className="text-5xl font-bold mb-6">Bienvenido a TiendaOnline</h1>
            <p className="text-xl mb-8 text-white/90">Descubre productos increíbles a los mejores precios</p>
            <div className="max-w-2xl mx-auto">
              <input
                type="text"
                placeholder="Buscar productos..."
                value={search}
                onChange={(e) => setSearch(e.target.value)}
                className="w-full px-6 py-4 rounded-full text-gray-800 text-lg focus:outline-none focus:ring-4 focus:ring-white/30"
              />
            </div>
          </div>
        </section>

        {/* Categories */}
        <section className="container mx-auto px-4 py-8">
          <div className="flex gap-3 overflow-x-auto pb-4">
            <button
              onClick={() => setSelectedCategory(null)}
              className={`px-6 py-2 rounded-full font-semibold whitespace-nowrap transition ${
                selectedCategory === null
                  ? 'bg-primary-600 text-white'
                  : 'bg-white text-gray-700 hover:bg-gray-100'
              }`}
            >
              Todas
            </button>
            {categories.map((category) => (
              <button
                key={category.id}
                onClick={() => setSelectedCategory(category.id)}
                className={`px-6 py-2 rounded-full font-semibold whitespace-nowrap transition ${
                  selectedCategory === category.id
                    ? 'bg-primary-600 text-white'
                    : 'bg-white text-gray-700 hover:bg-gray-100'
                }`}
              >
                {category.name}
              </button>
            ))}
          </div>
        </section>

        {/* Products Grid */}
        <section className="container mx-auto px-4 pb-16">
          {loading ? (
            <div className="text-center py-20">
              <div className="animate-spin rounded-full h-16 w-16 border-t-4 border-primary-600 mx-auto"></div>
            </div>
          ) : (
            <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
              {filteredProducts.map((product) => (
                <Link key={product.id} href={`/products/${product.id}`}>
                  <div className="card cursor-pointer h-full flex flex-col">
                    <div className="relative h-64 bg-gray-200">
                      <Image
                        src={product.image_url || 'https://via.placeholder.com/400'}
                        alt={product.name}
                        fill
                        className="object-cover"
                      />
                    </div>
                    <div className="p-4 flex-1 flex flex-col">
                      <h3 className="font-semibold text-lg mb-2 line-clamp-2">{product.name}</h3>
                      <p className="text-gray-600 text-sm mb-4 line-clamp-2 flex-1">{product.description}</p>
                      <div className="flex items-center justify-between">
                        <span className="text-2xl font-bold text-primary-600">${product.price}</span>
                        <div className="flex items-center">
                          <span className="text-yellow-500 mr-1">⭐</span>
                          <span className="text-sm font-semibold">{product.average_rating || '0.0'}</span>
                        </div>
                      </div>
                      <button className="mt-4 btn-primary w-full">
                        Ver Detalles
                      </button>
                    </div>
                  </div>
                </Link>
              ))}
            </div>
          )}

          {!loading && filteredProducts.length === 0 && (
            <div className="text-center py-20">
              <p className="text-xl text-gray-500">No se encontraron productos</p>
            </div>
          )}
        </section>

        {/* Footer */}
        <footer className="bg-gray-900 text-white py-12">
          <div className="container mx-auto px-4">
            <div className="grid grid-cols-1 md:grid-cols-4 gap-8">
              <div>
                <h3 className="text-xl font-bold mb-4">🛍️ TiendaOnline</h3>
                <p className="text-gray-400">La mejor experiencia de compra online</p>
              </div>
              <div>
                <h4 className="font-semibold mb-4">Enlaces</h4>
                <ul className="space-y-2 text-gray-400">
                  <li><Link href="/about" className="hover:text-white">Sobre Nosotros</Link></li>
                  <li><Link href="/contact" className="hover:text-white">Contacto</Link></li>
                  <li><Link href="/faq" className="hover:text-white">FAQ</Link></li>
                </ul>
              </div>
              <div>
                <h4 className="font-semibold mb-4">Soporte</h4>
                <ul className="space-y-2 text-gray-400">
                  <li><Link href="/shipping" className="hover:text-white">Envíos</Link></li>
                  <li><Link href="/returns" className="hover:text-white">Devoluciones</Link></li>
                  <li><Link href="/help" className="hover:text-white">Ayuda</Link></li>
                </ul>
              </div>
              <div>
                <h4 className="font-semibold mb-4">Síguenos</h4>
                <div className="flex space-x-4 text-2xl">
                  <a href="#" className="hover:text-primary-400">📘</a>
                  <a href="#" className="hover:text-primary-400">📷</a>
                  <a href="#" className="hover:text-primary-400">🐦</a>
                </div>
              </div>
            </div>
            <div className="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
              <p>© 2024 TiendaOnline. Todos los derechos reservados.</p>
            </div>
          </div>
        </footer>
      </div>
    </>
  );
}
