import { Route, Routes } from "react-router-dom";
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
      <Route path="/aboutUs" element={<AboutUs />} />
      <Route path="/contact" element={<Contact />} />
      <Route path="/TermsOfUse" element={<TermsOfUse />} />
      <Route path="/PrivacyPolicy" element={<PrivacyPolicy />} />
      <Route path="/Help" element={<Help />} />
      <Route path="/AdminDashboard" element={<AdminDashboard />} />
      </Route>
    </Routes>
  );
};

export default AppRoutes;
