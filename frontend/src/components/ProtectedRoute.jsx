import { Navigate, Outlet } from 'react-router-dom'
import { useAuthStore } from '../stores/authStore'

export default function ProtectedRoute({ requiredRole = null }) {
  const { isAuthenticated, hasRole } = useAuthStore()

  if (!isAuthenticated) {
    return <Navigate to="/login" replace />
  }

  if (requiredRole && !hasRole(requiredRole) && !hasRole('super_admin')) {
    return <Navigate to="/" replace />
  }

  return <Outlet />
}
