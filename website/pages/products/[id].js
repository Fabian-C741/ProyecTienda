import { useState, useEffect } from 'react';
import Head from 'next/head';
import Link from 'next/link';
import Image from 'next/image';
import { useRouter } from 'next/router';
import { productsAPI, cartAPI } from '../../lib/api';

export default function ProductDetail() {
  const router = useRouter();
  const { id } = router.query;
  const [product, setProduct] = useState(null);
  const [quantity, setQuantity] = useState(1);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    if (id) {
      loadProduct();
    }
  }, [id]);

  async function loadProduct() {
    try {
      setLoading(true);
      const response = await productsAPI.getById(id);
      setProduct(response.data.product);
    } catch (error) {
      console.error('Error loading product:', error);
    } finally {
      setLoading(false);
    }
  }

  async function handleAddToCart() {
    try {
      await cartAPI.add(product.id, quantity);
      alert('Producto agregado al carrito');
      router.push('/cart');
    } catch (error) {
      alert('Error al agregar al carrito. Por favor inicia sesión.');
      router.push('/login');
    }
  }

  if (loading) {
    return (
      <div className="min-h-screen flex items-center justify-center">
        <div className="animate-spin rounded-full h-16 w-16 border-t-4 border-primary-600"></div>
      </div>
    );
  }

  if (!product) {
    return (
      <div className="min-h-screen flex items-center justify-center">
        <div className="text-center">
          <p className="text-2xl mb-4">Producto no encontrado</p>
          <Link href="/" className="btn-primary">
            Volver al Inicio
          </Link>
        </div>
      </div>
    );
  }

  return (
    <>
      <Head>
        <title>{product.name} - TiendaOnline</title>
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

        {/* Breadcrumb */}
        <div className="container mx-auto px-4 py-4">
          <div className="flex items-center text-sm text-gray-600">
            <Link href="/" className="hover:text-primary-600">Inicio</Link>
            <span className="mx-2">/</span>
            <Link href="/" className="hover:text-primary-600">Productos</Link>
            <span className="mx-2">/</span>
            <span className="text-gray-900">{product.name}</span>
          </div>
        </div>

        {/* Product Detail */}
        <section className="container mx-auto px-4 pb-16">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-12">
            {/* Image */}
            <div className="relative aspect-square bg-white rounded-2xl overflow-hidden shadow-lg">
              <Image
                src={product.image_url || 'https://via.placeholder.com/800'}
                alt={product.name}
                fill
                className="object-cover"
              />
            </div>

            {/* Info */}
            <div>
              <h1 className="text-4xl font-bold mb-4">{product.name}</h1>
              
              <div className="flex items-center mb-6">
                <div className="flex items-center mr-4">
                  <span className="text-yellow-500 text-2xl mr-2">⭐</span>
                  <span className="text-xl font-semibold">{product.average_rating || '0.0'}</span>
                </div>
                <span className="text-gray-600">({product.total_reviews || 0} reviews)</span>
              </div>

              <div className="mb-6">
                <span className="text-5xl font-bold text-primary-600">${product.price}</span>
              </div>

              <p className="text-gray-700 text-lg mb-6 leading-relaxed">{product.description}</p>

              <div className="mb-6">
                <p className="text-sm font-semibold text-gray-600 mb-2">Stock disponible:</p>
                <p className={`text-lg font-bold ${product.stock > 10 ? 'text-green-600' : product.stock > 0 ? 'text-yellow-600' : 'text-red-600'}`}>
                  {product.stock > 0 ? `${product.stock} unidades` : 'Agotado'}
                </p>
              </div>

              {product.stock > 0 && (
                <>
                  <div className="mb-6">
                    <label className="block text-sm font-semibold text-gray-700 mb-2">
                      Cantidad:
                    </label>
                    <div className="flex items-center space-x-4">
                      <button
                        onClick={() => setQuantity(Math.max(1, quantity - 1))}
                        className="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold w-12 h-12 rounded-lg transition"
                      >
                        -
                      </button>
                      <span className="text-2xl font-bold w-16 text-center">{quantity}</span>
                      <button
                        onClick={() => setQuantity(Math.min(product.stock, quantity + 1))}
                        className="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold w-12 h-12 rounded-lg transition"
                      >
                        +
                      </button>
                    </div>
                  </div>

                  <button
                    onClick={handleAddToCart}
                    className="btn-primary w-full text-xl py-4"
                  >
                    🛒 Agregar al Carrito
                  </button>
                </>
              )}

              {product.stock === 0 && (
                <button disabled className="bg-gray-300 text-gray-600 w-full text-xl py-4 rounded-lg cursor-not-allowed">
                  Producto Agotado
                </button>
              )}

              <div className="mt-8 border-t pt-8">
                <h3 className="font-bold text-lg mb-4">Información del Producto</h3>
                <ul className="space-y-2 text-gray-700">
                  <li>• Categoría: {product.category?.name}</li>
                  <li>• SKU: {product.id}</li>
                  <li>• Envío gratis en pedidos superiores a $50</li>
                  <li>• Garantía de devolución de 30 días</li>
                </ul>
              </div>
            </div>
          </div>
        </section>
      </div>
    </>
  );
}
