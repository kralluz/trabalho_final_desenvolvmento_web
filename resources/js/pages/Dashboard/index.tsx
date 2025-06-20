import { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import Header from "@/components/Header";
import "./dashboard.style.css";
import ModalCreateAd from "@/components/ModalCreateAd";
import ModalEditAd from "@/components/ModalEditAd";
import ModalConfirmDelete from "@/components/ModalConfirmDelete";
import PostList, { CardItem } from "@/components/PostList";
import imagemTeste from "/resources/js/assets/images/imagemTeste.jpg";
import {
  getMyAdsenses,
  createAdsense,
  updateAdsense,
  deleteAdsense,
} from "@/api/adsense";

const Dashboard: React.FC = () => {
  const navigate = useNavigate();
  const [isModalOpen, setIsModalOpen] = useState<boolean>(false);
  const [isEditModalOpen, setIsEditModalOpen] = useState<boolean>(false);
  const [isDeleteModalOpen, setIsDeleteModalOpen] = useState<boolean>(false);
  const [selectedCard, setSelectedCard] = useState<CardItem | null>(null);
  const [cards, setCards] = useState<CardItem[]>([]);
  const [deleteLoading, setDeleteLoading] = useState<boolean>(false);

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
    setIsModalOpen(true);
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
      setIsModalOpen(false);
    } catch (err) {
      console.error(err);
    }
  };

  const handleEdit = (card: CardItem) => {
    setSelectedCard(card);
    setIsEditModalOpen(true);
  };

  const handleUpdateCard = async (id: number, data: { titulo: string; descricao: string; preco: string; imagemFile?: File }) => {
    try {
      await updateAdsense(id, data);
      const updatedData = await getMyAdsenses();
      setCards(updatedData);
      setIsEditModalOpen(false);
    } catch (err) {
      console.error(err);
      throw err; // Re-throw para o modal tratar
    }
  };

  const handleDelete = (card: CardItem) => {
    setSelectedCard(card);
    setIsDeleteModalOpen(true);
  };

  const handleConfirmDelete = async () => {
    if (!selectedCard) return;
    
    setDeleteLoading(true);
    try {
      await deleteAdsense(selectedCard.id);
      const updatedData = await getMyAdsenses();
      setCards(updatedData);
      setIsDeleteModalOpen(false);
      setSelectedCard(null);
    } catch (err) {
      console.error(err);
      throw err; // Re-throw para o modal tratar
    } finally {
      setDeleteLoading(false);
    }
  };

  return (
    <div className="dashboard-container">
      <header className="dashboard-header">
        <span className="dashboard-link" onClick={handleNavigate}>
          Ir para a página inicial
        </span>
        <button className="new-ad-button" onClick={handleNewAd}>
          Novo Anúncio
        </button>
      </header>

      <div className="dashboard-cards">
        <div className="cards-container">
          {cards.map((card) => (
            <div className="card" key={card.id}>
              <img
                src={card.imagem || undefined}
                alt="imagem do card"
                className="imagemCard"
              />
              <h3 className="card-title">{card.titulo}</h3>
              <p className="card-text">{card.descricao}</p>
              <p className="card-title">{card.preco}</p>
              <button onClick={() => handleEdit(card)}>Editar</button>
              <button onClick={() => handleDelete(card)}>Excluir</button>
            </div>
          ))}
        </div>
      </div>

      {/* Modal de Criação */}
      <ModalCreateAd
        isOpen={isModalOpen}
        onClose={() => setIsModalOpen(false)}
        onAddCard={handleAddCard}
      />

      {/* Modal de Edição */}
      <ModalEditAd
        isOpen={isEditModalOpen}
        onClose={() => {
          setIsEditModalOpen(false);
          setSelectedCard(null);
        }}
        onUpdateCard={handleUpdateCard}
        cardData={selectedCard}
      />

      {/* Modal de Confirmação de Deleção */}
      <ModalConfirmDelete
        isOpen={isDeleteModalOpen}
        onClose={() => {
          setIsDeleteModalOpen(false);
          setSelectedCard(null);
        }}
        onConfirm={handleConfirmDelete}
        cardData={selectedCard}
        loading={deleteLoading}
      />
    </div>
  );
};

export default Dashboard;
