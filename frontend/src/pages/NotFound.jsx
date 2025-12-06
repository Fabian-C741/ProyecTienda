import { Link } from 'react-router-dom'

export default function NotFound() {
  return (
    <div className="min-h-[70vh] flex items-center justify-center">
      <div className="text-center">
        <h1 className="text-6xl font-bold text-gray-900 mb-4">404</h1>
        <p className="text-2xl text-gray-600 mb-8">Página no encontrada</p>
        <Link to="/" className="btn btn-primary">
          Volver al Inicio
        </Link>
      </div>
    </div>
  )
}
