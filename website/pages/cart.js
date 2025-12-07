import { useState, useEffect } from 'react';
import Head from 'next/head';
import Link from 'next/link';
import Image from 'next/image';
import { useRouter } from 'next/router';
import { cartAPI } from '../lib/api';

export default function Cart() {
  const router = useRouter();
  const [cart, setCart] = useState({ items: [], total: 0, subtotal: 0 });
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    loadCart();
  }, []);

  async function loadCart() {
    try {
      setLoading(true);
      const response = await cartAPI.get();
      setCart(response.data.cart || { items: [], total: 0, subtotal: 0 });
    } catch (error) {
      console.error('Error loading cart:', error);
      if (error.response?.status === 401) {
        router.push('/login');
      }
    } finally {
      setLoading(false);
    }
  }

  async function handleUpdateQuantity(productId, newQuantity) {
    if (newQuantity < 1) {
      handleRemoveItem(productId);
      return;
    }
    try {
      const response = await cartAPI.update(productId, newQuantity);
      setCart(response.data.cart);
    } catch (error) {
      alert('Error al actualizar cantidad');
    }
  }

  async function handleRemoveItem(productId) {
    if (confirm('¿Deseas eliminar este producto del carrito?')) {
      try {
        const response = await cartAPI.remove(productId);
        setCart(response.data.cart);
      } catch (error) {
        alert('Error al eliminar producto');
      }
    }
  }

  function handleCheckout() {
    router.push('/checkout');
  }

  if (loading) {
    return (
      <div className="min-h-screen flex items-center justify-center">
        <div className="animate-spin rounded-full h-16 w-16 border-t-4 border-primary-600"></div>
      </div>
    );
  }

  return (
    <>
      <Head>
        <title>Carrito de Compras - TiendaOnline</title>
      </Head>

      <div className="min-h-screen bg-gray-50">
        {/* Header */}
        <header className="bg-gradient-to-r from-primary-600 to-purple-600 text-white">
          <div className="container mx-auto px-4 py-6">
            <div className="flex items-center justify-between">
              <Link href="/" className="text-3xl font-bold">
                🛍️ TiendaOnline
              </Link>
              <Link href="/login" className="bg-white text-primary-600 px-4 py-2 rounded-lg font-semibold hover:bg-primary-50 transition">
                Iniciar Sesión
              </Link>
            </div>
          </div>
        </header>

        <div className="container mx-auto px-4 py-12">
          <h1 className="text-4xl font-bold mb-8">Mi Carrito</h1>

          {cart.items.length === 0 ? (
            <div className="text-center py-20">
              <div className="text-8xl mb-6">🛒</div>
              <h2 className="text-2xl font-semibold mb-4">Tu carrito está vacío</h2>
              <p className="text-gray-600 mb-8">¡Agrega algunos productos para comenzar!</p>
              <Link href="/" className="btn-primary inline-block">
                Ir a Comprar
              </Link>
            </div>
          ) : (
            <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
              {/* Cart Items */}
              <div className="lg:col-span-2 space-y-4">
                {cart.items.map((item) => (
                  <div key={item.product_id} className="bg-white rounded-lg shadow-md p-6 flex items-center">
                    <div className="relative w-24 h-24 mr-6 flex-shrink-0">
                      <Image
                        src={item.product.image_url || 'https://via.placeholder.com/100'}
                        alt={item.product.name}
                        fill
                        className="object-cover rounded-lg"
                      />
                    </div>

                    <div className="flex-1">
                      <h3 className="font-semibold text-lg mb-2">{item.product.name}</h3>
                      <p className="text-primary-600 font-bold text-xl">${item.product.price}</p>
                    </div>

                    <div className="flex items-center space-x-4 mr-6">
                      <button
                        onClick={() => handleUpdateQuantity(item.product_id, item.quantity - 1)}
                        className="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold w-10 h-10 rounded-lg transition"
                      >
                        -
                      </button>
                      <span className="text-xl font-bold w-12 text-center">{item.quantity}</span>
                      <button
                        onClick={() => handleUpdateQuantity(item.product_id, item.quantity + 1)}
                        className="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold w-10 h-10 rounded-lg transition"
                      >
                        +
                      </button>
                    </div>

                    <div className="text-right">
                      <p className="text-2xl font-bold mb-2">${item.subtotal}</p>
                      <button
                        onClick={() => handleRemoveItem(item.product_id)}
                        className="text-red-600 hover:text-red-800 text-sm font-semibold"
                      >
                        🗑️ Eliminar
                      </button>
                    </div>
                  </div>
                ))}
              </div>

              {/* Summary */}
              <div>
                <div className="bg-white rounded-lg shadow-md p-6 sticky top-4">
                  <h2 className="text-2xl font-bold mb-6">Resumen del Pedido</h2>

                  <div className="space-y-3 mb-6">
                    <div className="flex justify-between text-gray-700">
                      <span>Subtotal:</span>
                      <span className="font-semibold">${cart.subtotal}</span>
                    </div>
                    <div className="flex justify-between text-gray-700">
                      <span>Envío:</span>
                      <span className="font-semibold">${cart.shipping || '0.00'}</span>
                    </div>
                    <div className="flex justify-between text-gray-700">
                      <span>Impuestos:</span>
                      <span className="font-semibold">${cart.tax || '0.00'}</span>
                    </div>
                    <div className="border-t pt-3 mt-3">
                      <div className="flex justify-between text-2xl font-bold">
                        <span>Total:</span>
                        <span className="text-primary-600">${cart.total}</span>
                      </div>
                    </div>
                  </div>

                  <button
                    onClick={handleCheckout}
                    className="btn-primary w-full text-lg py-4"
                  >
                    Proceder al Pago
                  </button>

                  <Link href="/" className="block text-center mt-4 text-primary-600 hover:text-primary-700 font-semibold">
                    ← Continuar Comprando
                  </Link>

                  <div className="mt-6 pt-6 border-t">
                    <p className="text-sm text-gray-600 mb-2">✓ Envío gratis en pedidos +$50</p>
                    <p className="text-sm text-gray-600 mb-2">✓ Devolución en 30 días</p>
                    <p className="text-sm text-gray-600">✓ Pago seguro</p>
                  </div>
                </div>
              </div>
            </div>
          )}
        </div>
      </div>
    </>
  );
}
