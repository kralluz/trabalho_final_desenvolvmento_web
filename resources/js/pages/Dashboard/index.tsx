import { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import Header from "@/components/Header";
import "./dashboard.style.css";
import ModalCreateAd from "@/components/ModalCreateAd";
import ModalEditAd from "@/components/ModalEditAd";
import ModalConfirmDelete from "@/components/ModalConfirmDelete";
import PostList, { CardItem } from "@/components/PostList";
import { Adsense } from "@/types/adsense";
import {
  getMyAdsenses,
  createAdsense,
  updateAdsense,
  deleteAdsense,
} from "@/api/adsense";

const Dashboard: React.FC = () => {
  const navigate = useNavigate();
  const [isCreateModalOpen, setIsCreateModalOpen] = useState<boolean>(false);
  const [isEditModalOpen, setIsEditModalOpen] = useState<boolean>(false);
  const [isDeleteModalOpen, setIsDeleteModalOpen] = useState<boolean>(false);
  const [cards, setCards] = useState<CardItem[]>([]);
  const [selectedCard, setSelectedCard] = useState<CardItem | null>(null);

  useEffect(() => {
    const fetchCards = async () => {
      try {
        const data = await getMyAdsenses();
        setCards(data);
      } catch (err) {
        console.error(err);
      }
    };
    fetchCards();
  }, []);

  const handleNavigate = () => {
    navigate("/home");
  };

  const handleNewAd = () => {
    setIsCreateModalOpen(true);
  };

  const handleAddCard = async (newCardData: { 
    titulo: string; 
    descricao: string; 
    preco: string; 
    imagemFile: File 
  }) => {
    try {
      await createAdsense({
        ...newCardData,
        images: [newCardData.imagemFile]
      });
      const data = await getMyAdsenses();
      setCards(data);
      setIsCreateModalOpen(false);
    } catch (err) {
      console.error(err);
    }
  };

  const handleEdit = (card: CardItem) => {
    setSelectedCard(card);
    setIsEditModalOpen(true);
  };

  const handleEditSubmit = async (editedData: {
    titulo: string;
    descricao: string;
    preco: string;
    imagemFile?: File;
  }) => {
    if (!selectedCard) return;
    
    try {
      await updateAdsense(selectedCard.id, editedData);
      const data = await getMyAdsenses();
      setCards(data);
      setIsEditModalOpen(false);
      setSelectedCard(null);
    } catch (err) {
      console.error(err);
    }
  };

  const handleDelete = (card: CardItem) => {
    setSelectedCard(card);
    setIsDeleteModalOpen(true);
  };

  const handleDeleteConfirm = async () => {
    if (!selectedCard) return;
    
    try {
      await deleteAdsense(selectedCard.id);
      const data = await getMyAdsenses();
      setCards(data);
      setIsDeleteModalOpen(false);
      setSelectedCard(null);
    } catch (err) {
      console.error(err);
    }
  };

  return (
    <div className="dashboard-container">
      <header className="dashboard-header">
        <div className="dashboard-title">
          Meus Anúncios
          <span className="dashboard-link" onClick={handleNavigate}>
            Ir para Home
          </span>
        </div>
        <button className="new-ad-button" onClick={handleNewAd}>
          Novo Anúncio
        </button>
      </header>

      <div className="dashboard-cards">
        <PostList 
          cards={cards.map(card => ({
            ...card,
            status: 'Ativo',
            isNew: true // ou lógica para "Novo"
          }))}
          onEdit={handleEdit}
          onDelete={handleDelete}
          showActions
          showLabels
        />
      </div>

      {/* Modal de Criação */}
      <ModalCreateAd
        isOpen={isCreateModalOpen}
        onClose={() => setIsCreateModalOpen(false)}
        onAddCard={handleAddCard}
      />

      {/* Modal de Edição */}
      {selectedCard && (
        <ModalEditAd
          isOpen={isEditModalOpen}
          onClose={() => {
            setIsEditModalOpen(false);
            setSelectedCard(null);
          }}
          onSubmit={handleEditSubmit}
          initialData={{
            ...selectedCard,
            imagem: selectedCard.imagem || undefined
          }}
        />
      )}

      {/* Modal de Confirmação de Deleção */}
      <ModalConfirmDelete
        isOpen={isDeleteModalOpen}
        onClose={() => {
          setIsDeleteModalOpen(false);
          setSelectedCard(null);
        }}
        onConfirm={handleDeleteConfirm}
        title="Confirmar exclusão"
        message={`Tem certeza que deseja excluir o anúncio "${selectedCard?.titulo}"? Esta ação não pode ser desfeita.`}
      />
    </div>
  );
};

export default Dashboard;
