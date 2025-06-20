import React from 'react';
import { Adsense } from '../../types/adsense';
import './card.style.css';

interface CardItemProps {
  ad: Adsense;
  onEdit: (ad: Adsense) => void;
  onDelete: (ad: Adsense) => void;
}

const CardItem: React.FC<CardItemProps> = ({ ad, onEdit, onDelete }) => {
  const formatPrice = (price: number) => {
    return new Intl.NumberFormat('pt-BR', {
      style: 'currency',
      currency: 'BRL'
    }).format(price);
  };

  const isNew = () => {
    const createdDate = new Date(ad.created_at);
    const now = new Date();
    const diffTime = Math.abs(now.getTime() - createdDate.getTime());
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    return diffDays <= 7;
  };

  return (
    <div className="card">
      <div className="card-header">
        {isNew() && (
          <span className="card-label card-label-new">Novo</span>
        )}
        <span className={`card-label card-label-status`}>
          {ad.status ? 'Ativo' : 'Inativo'}
        </span>
      </div>

      <img
        src={ad.image?.url || '/images/placeholder.jpg'}
        alt={ad.title}
        className="imagemCard"
      />

      <div className="card-content">
        <h3 className="card-title">{ad.title}</h3>
        <p className="card-text">{ad.description}</p>
        
        <div className="card-price-container">
          <p className="card-price">{formatPrice(ad.price)}</p>
        </div>
      </div>

      <div className="card-actions">
        <button
          className="edit-button"
          onClick={() => onEdit(ad)}
          aria-label="Editar anúncio"
        >
          Editar
        </button>
        <button
          className="delete-button"
          onClick={() => onDelete(ad)}
          aria-label="Excluir anúncio"
        >
          Excluir
        </button>
      </div>
    </div>
  );
};

export default CardItem;
