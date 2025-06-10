import { useState } from "react";
import { useLocation, useNavigate } from "react-router-dom";
import "./header.style.css";

const Header: React.FC = () => {
  const [isAuthenticated, setIsAuthenticated] = useState<boolean>(true);
  const navigate = useNavigate();
  const path = useLocation();

  const renderHeaderContent = () => {
    if (path.pathname === "/home") {
      return isAuthenticated ? (
        <button className="header-button" onClick={() => navigate("/dashboard")}>
          Dashboard
        </button>
      ) : (
        <button className="header-button" onClick={() => navigate("/login")}>
          Iniciar Sessão
        </button>
      );
    }

    if (path.pathname === "/login") {
      return (
        <button className="header-button" onClick={() => navigate("/register")}>
          Criar Conta
        </button>
      );
    }

    if (path.pathname === "/register") {
      return (
        <button className="header-button" onClick={() => navigate("/login")}>
          Iniciar Sessão
        </button>
      );
    }

    if (path.pathname === "/dashboard" || path.pathname === "/admin") {
      return (
        <button className="header-button secondary" onClick={() => navigate("/home")}>
          Home
        </button>
      );
    }

    return (
      <>
        <button className="header-button" onClick={() => navigate("/login")}>Iniciar Sessão</button>
        <button className="header-button" onClick={() => navigate("/dashboard")}>Dashboard</button>
        <button className="header-button" onClick={() => navigate("/admin")}>Admin</button>
        <button className="header-button secondary" onClick={() => navigate("/home")}>Home</button>
      </>
    );
  };

  return (
    <header className="header">
      <div className="header-logo" onClick={() => navigate("/home")}>
        <img src="/images/logoBranca.png" alt="Logo Domus" className="logo-img" />
        <span>Domus</span>
      </div>
      <nav className="header-buttons">
        {renderHeaderContent()}
      </nav>
    </header>
  );
};

export default Header;
