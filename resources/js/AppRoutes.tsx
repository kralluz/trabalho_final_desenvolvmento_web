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
import { useAuth } from "./hooks/useAuth";

const AppRoutes: React.FC = () => {
  const { isAuthenticated } = useAuth();

  // Function to check auth state and redirect for login/register pages
  const renderAuthRoute = (Component: React.ComponentType) => {
    return isAuthenticated() ? <Navigate to="/dashboard" replace /> : <Component />;
  };

  // Function to check auth state and redirect for protected pages
  const renderProtectedRoute = (Component: React.ComponentType) => {
    return isAuthenticated() ? <Component /> : <Navigate to="/login" replace />;
  };

  return (
    <Routes>
      <Route path="/" element={<MainLayout />}>
        {/* Redirect root to home or dashboard based on auth */}
        <Route index element={
          isAuthenticated() ? <Navigate to="/dashboard" replace /> : <Navigate to="/home" replace />
        } />
        
        {/* Public routes */}
        <Route path="/login" element={renderAuthRoute(LoginPage)} />
        <Route path="/register" element={renderAuthRoute(RegisterPage)} />
        <Route path="/home" element={<Home />} />
        <Route path="/aboutUs" element={<AboutUs />} />
        <Route path="/contact" element={<Contact />} />
        <Route path="/TermsOfUse" element={<TermsOfUse />} />
        <Route path="/PrivacyPolicy" element={<PrivacyPolicy />} />
        <Route path="/Help" element={<Help />} />
        
        {/* Protected routes */}
        <Route path="/dashboard" element={renderProtectedRoute(Dashboard)} />
        <Route path="/admin" element={renderProtectedRoute(Admin)} />
        <Route path="/AdminDashboard" element={renderProtectedRoute(AdminDashboard)} />
        
        {/* 404 route */}
        <Route path="*" element={<NotFound />} />
      </Route>
    </Routes>
  );
};

export default AppRoutes;
