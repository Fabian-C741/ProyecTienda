import { Link } from 'react-router-dom'
import { Facebook, Twitter, Instagram, Mail, Phone, MapPin } from 'lucide-react'

export default function Footer() {
  return (
    <footer className="bg-gray-800 text-white mt-16">
      <div className="container mx-auto px-4 py-12">
        <div className="grid grid-cols-1 md:grid-cols-4 gap-8">
          {/* Company Info */}
          <div>
            <h3 className="text-xl font-bold mb-4">TiendaOnline</h3>
            <p className="text-gray-400 mb-4">
              Tu marketplace de confianza para comprar y vender productos.
            </p>
            <div className="flex space-x-4">
              <a href="#" className="text-gray-400 hover:text-white">
                <Facebook className="h-5 w-5" />
              </a>
              <a href="#" className="text-gray-400 hover:text-white">
                <Twitter className="h-5 w-5" />
              </a>
              <a href="#" className="text-gray-400 hover:text-white">
                <Instagram className="h-5 w-5" />
              </a>
            </div>
          </div>

          {/* Quick Links */}
          <div>
            <h4 className="text-lg font-semibold mb-4">Enlaces Rápidos</h4>
            <ul className="space-y-2">
              <li><Link to="/products" className="text-gray-400 hover:text-white">Productos</Link></li>
              <li><Link to="/about" className="text-gray-400 hover:text-white">Sobre Nosotros</Link></li>
              <li><Link to="/contact" className="text-gray-400 hover:text-white">Contacto</Link></li>
              <li><Link to="/faq" className="text-gray-400 hover:text-white">Preguntas Frecuentes</Link></li>
            </ul>
          </div>

          {/* Customer Service */}
          <div>
            <h4 className="text-lg font-semibold mb-4">Servicio al Cliente</h4>
            <ul className="space-y-2">
              <li><Link to="/shipping" className="text-gray-400 hover:text-white">Envíos</Link></li>
              <li><Link to="/returns" className="text-gray-400 hover:text-white">Devoluciones</Link></li>
              <li><Link to="/privacy" className="text-gray-400 hover:text-white">Privacidad</Link></li>
              <li><Link to="/terms" className="text-gray-400 hover:text-white">Términos</Link></li>
            </ul>
          </div>

          {/* Contact */}
          <div>
            <h4 className="text-lg font-semibold mb-4">Contacto</h4>
            <ul className="space-y-2 text-gray-400">
              <li className="flex items-center space-x-2">
                <Mail className="h-4 w-4" />
                <span>info@tiendaonline.com</span>
              </li>
              <li className="flex items-center space-x-2">
                <Phone className="h-4 w-4" />
                <span>+1 234 567 8900</span>
              </li>
              <li className="flex items-center space-x-2">
                <MapPin className="h-4 w-4" />
                <span>Ciudad, País</span>
              </li>
            </ul>
          </div>
        </div>

        <div className="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
          <p>&copy; {new Date().getFullYear()} TiendaOnline. Todos los derechos reservados.</p>
        </div>
      </div>
    </footer>
  )
}
