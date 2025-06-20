import React, { useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { useAuth } from "../../contexts/AuthContext";
import "./admindashboard.style.css";

const AdminDashboard: React.FC = () => {
  const { user, isAuthenticated } = useAuth();
  const navigate = useNavigate();

 // useEffect(() => {
 //   if (!isAuthenticated || user?.role !== "admin") {
      // Redireciona para o login caso o usuário não seja um admin ou não esteja autenticado
 //     navigate("/login");
 //   }
 // }, [isAuthenticated, user, navigate]);

  return (
    <div className="admin-dashboard">
      <h1>Painel do Administrador</h1>

      <section className="dashboard-section">
        <h2>Resumo</h2>
        <ul>
          <li>Total de imóveis cadastrados: 152</li>
          <li>Imóveis disponíveis: 89</li>
          <li>Imóveis alugados/vendidos: 63</li>
          <li>Usuários registrados: 74</li>
        </ul>
      </section>

      <section className="dashboard-section">
        <h2>Gerenciamento</h2>
        <div className="admin-actions">
          <button onClick={() => navigate("/admin/imoveis")}>Gerenciar Imóveis</button>
          <button onClick={() => navigate("/admin/usuarios")}>Gerenciar Usuários</button>
          <button onClick={() => navigate("/admin/mensagens")}>Mensagens Recebidas</button>
          <button onClick={() => navigate("/admin/configuracoes")}>Configurações do Site</button>
        </div>
      </section>
    </div>
  );
};

export default AdminDashboard;
