import React from 'react';
import './ModalConfirmDelete.style.css';
import { CardItem } from '../PostList';

type ModalConfirmDeleteProps = {
  isOpen: boolean;
  onClose: () => void;
  onConfirm: () => Promise<void>;
  cardData: CardItem | null;
  loading?: boolean;
};

export default function ModalConfirmDelete({ 
  isOpen, 
  onClose, 
  onConfirm, 
  cardData, 
  loading = false 
}: ModalConfirmDeleteProps) {
  if (!isOpen || !cardData) return null;

  const handleConfirm = async () => {
    try {
      await onConfirm();
      onClose();
    } catch (error) {
      console.error('Erro ao deletar anúncio:', error);
    }
  };

  return (
    <div className="modal-overlay">
      <div className="modal-content modal-confirm-delete">
        <div className="modal-header">
          <h2>Confirmar Exclusão</h2>
        </div>
        
        <div className="modal-body">
          <div className="warning-icon">⚠️</div>
          <p>Tem certeza que deseja excluir este anúncio?</p>
          
          <div className="card-preview">
            <h4>{cardData.titulo}</h4>
            <p>{cardData.descricao}</p>
            <span className="price">{cardData.preco}</span>
          </div>
          
          <p className="warning-text">
            Esta ação não pode ser desfeita.
          </p>
        </div>

        <div className="modal-actions">
          <button 
            type="button" 
            onClick={onClose}
            className="cancel-button"
            disabled={loading}
          >
            Cancelar
          </button>
          <button 
            type="button" 
            onClick={handleConfirm}
            className="delete-button"
            disabled={loading}
          >
            {loading ? 'Excluindo...' : 'Excluir'}
          </button>
        </div>
      </div>
    </div>
  );
}
