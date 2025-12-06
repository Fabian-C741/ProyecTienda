import { Link } from 'react-router-dom'

export default function ProductCard({ product }) {
  const hasDiscount = product.compare_price && product.compare_price > product.price
  const discountPercent = hasDiscount
    ? Math.round(((product.compare_price - product.price) / product.compare_price) * 100)
    : 0

  return (
    <Link to={`/products/${product.slug}`} className="card group">
      <div className="relative overflow-hidden">
        <img
          src={product.featured_image || '/placeholder-product.jpg'}
          alt={product.name}
          className="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-300"
        />
        
        {hasDiscount && (
          <div className="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded-md text-sm font-semibold">
            -{discountPercent}%
          </div>
        )}

        {product.is_featured && (
          <div className="absolute top-2 left-2 bg-primary-600 text-white px-2 py-1 rounded-md text-sm font-semibold">
            Destacado
          </div>
        )}

        {!product.isInStock && (
          <div className="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
            <span className="text-white font-semibold text-lg">Sin Stock</span>
          </div>
        )}
      </div>

      <div className="p-4">
        <h3 className="font-semibold text-gray-800 mb-2 line-clamp-2 group-hover:text-primary-600">
          {product.name}
        </h3>

        {product.category && (
          <p className="text-sm text-gray-500 mb-2">{product.category.name}</p>
        )}

        <div className="flex items-center justify-between">
          <div>
            <span className="text-2xl font-bold text-gray-900">
              ${parseFloat(product.price).toFixed(2)}
            </span>
            {hasDiscount && (
              <span className="ml-2 text-sm text-gray-500 line-through">
                ${parseFloat(product.compare_price).toFixed(2)}
              </span>
            )}
          </div>
        </div>

        {product.track_inventory && (
          <div className="mt-2">
            <p className="text-sm text-gray-600">
              Stock: {product.stock} unidades
            </p>
          </div>
        )}
      </div>
    </Link>
  )
}
