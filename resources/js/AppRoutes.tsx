import { Route, Routes } from "react-router-dom";
import LoginPage from "./pages/LoginPage/Index";
import RegisterPage from "./pages/RegisterPage/Index";
import NotFound from "./pages/NotFound";
import Home from "./pages/Home";
import Dashboard from "./pages/Dashboard";
import Admin from "./pages/Admin";
import MainLayout from "./Layout/MainLayout";

const AppRoutes: React.FC = () => {
  return (
    <Routes>
    <Route path="/" element={<MainLayout />}>
      <Route path="/login" element={<LoginPage />} />
      <Route path="/register" element={<RegisterPage />} />
      <Route path="/home" element={<Home />} />
      <Route path="/dashboard" element={<Dashboard />} />
      <Route path="/admin" element={<Admin />} />
      <Route path="*" element={<NotFound />} />
      </Route>
    </Routes>
  );
};

export default AppRoutes;
