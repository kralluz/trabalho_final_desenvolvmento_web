import { Navigate, useLocation } from 'react-router-dom';
import { useAuth } from '../hooks/useAuth';

interface ProtectedRouteProps {
  children: React.ReactNode;
  requireAuth?: boolean;
  redirectTo?: string;
}

const ProtectedRoute: React.FC<ProtectedRouteProps> = ({ 
  children, 
  requireAuth = true, 
  redirectTo 
}) => {
  const { isAuthenticated, initializing } = useAuth();
  const location = useLocation();

  // Show loading while checking auth state
  if (initializing) {
    return (
      <div style={{ 
        display: 'flex', 
        justifyContent: 'center', 
        alignItems: 'center', 
        height: '100vh',
        flexDirection: 'column',
        gap: '20px'
      }}>
        <div style={{
          width: '50px',
          height: '50px',
          border: '4px solid #f3f3f3',
          borderTop: '4px solid #ff6f00',
          borderRadius: '50%',
          animation: 'spin 1s linear infinite'
        }}></div>
        <p>Verificando autenticação...</p>
      </div>
    );
  }

  const authenticated = isAuthenticated();

  // If route requires authentication and user is not authenticated
  if (requireAuth && !authenticated) {
    return <Navigate to={redirectTo || '/login'} state={{ from: location }} replace />;
  }

  // If route doesn't require authentication (like login/register) and user is authenticated
  if (!requireAuth && authenticated) {
    return <Navigate to={redirectTo || '/home'} replace />;
  }

  // If user meets requirements, render the component
  return <>{children}</>;
};

export default ProtectedRoute;
