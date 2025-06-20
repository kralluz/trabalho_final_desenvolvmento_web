import { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import Header from "@/components/Header";
import "./dashboard.style.css";
import ModalCreateAd from "@/components/ModalCreateAd";
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
  const [cards, setCards] = useState<CardItem[]>([]);

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

  const handleEdit = async (card: CardItem) => {
    const titulo = prompt("Editar título", card.titulo);
    if (titulo === null) return;
    const descricao = prompt("Editar descrição", card.descricao);
    if (descricao === null) return;
    const preco = prompt("Editar preço (ex: R$100.00)", card.preco);
    if (preco === null) return;
    try {
      await updateAdsense(card.id, { titulo, descricao, preco });
      const data = await getMyAdsenses();
      setCards(data);
    } catch (err) {
      console.error(err);
    }
  };

  const handleDelete = async (id: number) => {
    if (!window.confirm("Confirma exclusão deste anúncio?")) return;
    try {
      await deleteAdsense(id);
      const data = await getMyAdsenses();
      setCards(data);
    } catch (err) {
      console.error(err);
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
              <button onClick={() => handleDelete(card.id)}>Excluir</button>
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
    </div>
  );
};

export default Dashboard;
