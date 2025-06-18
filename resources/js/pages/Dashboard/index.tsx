import React, { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { useAuth } from "../../contexts/AuthContext";
import { useAdsense } from "../../contexts/AdsenseContext";
import AdsenseCard from "../../components/AdsenseCard";
import AdsenseForm from "../../components/AdsenseForm";
import { Adsense, CreateAdsenseRequest } from "../../services/api";
import "./dashboard.style.css";

const Dashboard: React.FC = () => {
  const navigate = useNavigate();
  const { user, isAuthenticated, logout } = useAuth();
  const { 
    adsenseList, 
    loading, 
    error, 
    loadAdsenses,
    createAdsense, 
    deleteAdsense, 
    updateAdsense 
  } = useAdsense();

  const [showForm, setShowForm] = useState(false);
  const [editingAdsense, setEditingAdsense] = useState<Adsense | null>(null);

  // Redirect to login if not authenticated
  useEffect(() => {
    if (!isAuthenticated) {
      navigate("/login");
      return;
    }
  }, [isAuthenticated, navigate]);

  const handleCreateAdsense = async (data: CreateAdsenseRequest): Promise<boolean> => {
    const success = await createAdsense(data);
    if (success) {
      setShowForm(false);
      return true;
    }
    return false;
  };

  const handleUpdateAdsense = async (data: CreateAdsenseRequest): Promise<boolean> => {
    if (!editingAdsense) return false;
    
    const success = await updateAdsense(editingAdsense.id, data);
    if (success) {
      setEditingAdsense(null);
      setShowForm(false);
      return true;
    }
    return false;
  };

  const handleDeleteAdsense = async (id: number) => {
    const success = await deleteAdsense(id);
    if (success) {
      // Optionally show success message
      console.log("Anúncio deletado com sucesso!");
    }
  };

  const handleEditAdsense = (adsense: Adsense) => {
    setEditingAdsense(adsense);
    setShowForm(true);
  };

  const handleCancelForm = () => {
    setShowForm(false);
    setEditingAdsense(null);
  };

  const handleLogout = () => {
    logout();
    navigate("/login");
  };

  if (!isAuthenticated || !user) {
    return (
      <div className="dashboard-loading">
        <p>Carregando...</p>
      </div>
    );
  }

  return (
    <div className="dashboard-container">
      <header className="dashboard-header">
        <div className="dashboard-header-content">
          <div className="dashboard-user-info">
            <h1>Dashboard</h1>
            <p>Bem-vindo, <strong>{user.name}</strong>!</p>
          </div>
          <div className="dashboard-header-actions">
            <button onClick={() => navigate("/home")} className="btn btn-outline">
              Página Inicial
            </button>
            <button onClick={handleLogout} className="btn btn-secondary">
              Sair
            </button>
          </div>
        </div>
      </header>

      <main className="dashboard-main">
        {error && (
          <div className="error-message-banner">
            <p>{error}</p>
            <button onClick={loadAdsenses} className="btn btn-sm">
              Tentar novamente
            </button>
          </div>
        )}

        <section className="dashboard-section">
          <div className="section-header">
            <h2>Meus Anúncios</h2>
            <button 
              onClick={() => setShowForm(true)} 
              className="btn btn-primary"
              disabled={loading}
            >
              + Novo Anúncio
            </button>
          </div>

          {showForm && (
            <AdsenseForm
              onSubmit={editingAdsense ? handleUpdateAdsense : handleCreateAdsense}
              onCancel={handleCancelForm}
              initialData={editingAdsense}
              isLoading={loading}
            />
          )}

          {loading && !showForm && (
            <div className="loading-container">
              <p>Carregando anúncios...</p>
            </div>
          )}

          {!loading && adsenseList.length === 0 && (
            <div className="empty-state">
              <h3>Nenhum anúncio encontrado</h3>
              <p>Você ainda não criou nenhum anúncio. Clique no botão "Novo Anúncio" para começar!</p>
            </div>
          )}

          {!loading && adsenseList.length > 0 && (
            <div className="adsense-list">
              {adsenseList.map((adsense) => (
                <AdsenseCard
                  key={adsense.id}
                  adsense={adsense}
                  onDelete={handleDeleteAdsense}
                  onEdit={handleEditAdsense}
                />
              ))}
            </div>
          )}
        </section>

        <section className="dashboard-stats">
          <div className="stats-grid">
            <div className="stat-card">
              <h3>Total de Anúncios</h3>
              <p className="stat-number">{adsenseList.length}</p>
            </div>
            <div className="stat-card">
              <h3>Valor Total</h3>
              <p className="stat-number">
                {new Intl.NumberFormat('pt-BR', {
                  style: 'currency',
                  currency: 'BRL'
                }).format(
                  adsenseList.reduce((total, adsense) => total + adsense.price, 0)
                )}
              </p>
            </div>
          </div>
        </section>
      </main>
    </div>
  );
};

export default Dashboard;