import { useState } from "react";
import { useNavigate, useLocation } from "react-router-dom";
import { useAuth } from "../../hooks/useAuth";
import ModalEditUser from "../ModalEditUser";
import "./header.style.css";
import LogoBranca from "/resources/js/assets/images/LogoBranca.png";

const Header: React.FC = () => {
  const { isAuthenticated, isAdmin, logout, user, updateUser } = useAuth();
  const navigate = useNavigate();
  const { pathname } = useLocation();
  const [isEditModalOpen, setIsEditModalOpen] = useState(false);

  const handleLogout = async () => {
    await logout();
    navigate('/login');
  };

  const handleEditProfile = async (data: {
    name: string;
    email: string;
    currentPassword?: string;
    newPassword?: string;
  }) => {
    try {
      await updateUser(data);
      setIsEditModalOpen(false);
    } catch (error) {
      console.error('Erro ao atualizar usuário:', error);
    }
  };

  const renderNavButtons = () => {
    // If not authenticated, show login/register options
    if (!isAuthenticated()) {
      if (pathname === '/register') {
        return (
          <button className="header-button" onClick={() => navigate('/login')}>
            Iniciar Sessão
          </button>
        );
      }
      if (pathname === '/login') {
        return (
          <button className="header-button" onClick={() => navigate('/register')}>
            Criar Conta
          </button>
        );
      }
      // Default for unauthenticated users
      return (
        <button className="header-button" onClick={() => navigate('/login')}>
          Iniciar Sessão
        </button>
      );
    }

    // For authenticated users
    return (
      <>
        {pathname !== '/home' && (
          <button className="header-button" onClick={() => navigate('/home')}>
            Home
          </button>
        )}
        {pathname !== '/dashboard' && (
          <button className="header-button" onClick={() => navigate('/dashboard')}>
            Meus Anúncios
          </button>
        )}
        {isAdmin() && pathname !== '/admin' && (
          <button className="header-button" onClick={() => navigate('/admin')}>
            Admin
          </button>
        )}
        <button 
          className="header-button"
          onClick={() => setIsEditModalOpen(true)}
        >
          Perfil
        </button>
        <button className="header-button secondary" onClick={handleLogout}>
          Sair
        </button>
      </>
    );
  };

  // Logo clickable only when authenticated
  const handleLogoClick = () => {
    if (isAuthenticated()) {
      navigate('/home');
    }
  };

  return (
    <>
      <header className="header">
        <div className="header-logo" onClick={handleLogoClick} style={{ cursor: isAuthenticated() ? 'pointer' : 'default' }}>
          <img src={LogoBranca} alt="Logo Domus" className="logo-img" />
        </div>
        <nav className="header-buttons">
          {renderNavButtons()}
        </nav>
      </header>

      {/* Modal de Edição de Perfil */}
      {user && (
        <ModalEditUser
          isOpen={isEditModalOpen}
          onClose={() => setIsEditModalOpen(false)}
          onSubmit={handleEditProfile}
          initialData={{
            name: user.name,
            email: user.email
          }}
        />
      )}
    </>
  );
};

export default Header;
