import { Route, Routes, Navigate } from "react-router-dom";
import LoginPage from "./pages/LoginPage/Index";
import RegisterPage from "./pages/RegisterPage/Index";
import NotFound from "./pages/NotFound";
import Home from "./pages/Home";
import Dashboard from "./pages/Dashboard";
import Admin from "./pages/Admin";
import Contact from "./pages/ContactPage";
import AboutUs from "./pages/AboutUs";
import MainLayout from "./Layout/MainLayout";
import TermsOfUse from "./pages/TermsOfUsePage";
import PrivacyPolicy from "./pages/PrivacyPolicyPage";
import Help from "./pages/HelpPage";
import AdminDashboard from "./pages/AdminDashboard";
import ProtectedRoute from "./components/ProtectedRoute";
import { useAuth } from "./hooks/useAuth";

const AppRoutes: React.FC = () => {
  const { isAuthenticated, initializing } = useAuth();

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
        <p>Carregando...</p>
      </div>
    );
  }

  return (
    <Routes>
      <Route path="/" element={<MainLayout />}>
        {/* Redirect root based on auth state */}
        <Route index element={
          isAuthenticated() ? <Navigate to="/home" replace /> : <Navigate to="/login" replace />
        } />
        
        {/* Auth routes - redirect to home if already logged in */}
        <Route path="/login" element={
          <ProtectedRoute requireAuth={false}>
            <LoginPage />
          </ProtectedRoute>
        } />
        <Route path="/register" element={
          <ProtectedRoute requireAuth={false}>
            <RegisterPage />
          </ProtectedRoute>
        } />
        
        {/* Public routes - accessible to everyone */}
        <Route path="/aboutUs" element={<AboutUs />} />
        <Route path="/contact" element={<Contact />} />
        <Route path="/TermsOfUse" element={<TermsOfUse />} />
        <Route path="/PrivacyPolicy" element={<PrivacyPolicy />} />
        <Route path="/Help" element={<Help />} />
        
        {/* Semi-protected routes - accessible to everyone but with different behavior */}
        <Route path="/home" element={<Home />} />
        
        {/* Protected routes - only accessible when logged in */}
        <Route path="/dashboard" element={
          <ProtectedRoute requireAuth={true}>
            <Dashboard />
          </ProtectedRoute>
        } />
        <Route path="/admin" element={
          <ProtectedRoute requireAuth={true}>
            <Admin />
          </ProtectedRoute>
        } />
        <Route path="/AdminDashboard" element={
          <ProtectedRoute requireAuth={true}>
            <AdminDashboard />
          </ProtectedRoute>
        } />
        
        {/* 404 route */}
        <Route path="*" element={<NotFound />} />
      </Route>
    </Routes>
  );
};

export default AppRoutes;
