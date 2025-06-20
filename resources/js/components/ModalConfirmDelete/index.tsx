import React from 'react';
import './ModalConfirmDelete.style.css';

interface ModalConfirmDeleteProps {
  isOpen: boolean;
  onClose: () => void;
  onConfirm: () => void;
  title?: string;
  message?: string;
}

const ModalConfirmDelete: React.FC<ModalConfirmDeleteProps> = ({
  isOpen,
  onClose,
  onConfirm,
  title = "Confirmar exclusão",
  message = "Tem certeza que deseja excluir este anúncio? Esta ação não pode ser desfeita."
}) => {
  if (!isOpen) return null;

  return (
    <div className="delete-modal-overlay" onClick={onClose}>
      <div className="delete-modal-content" onClick={e => e.stopPropagation()}>
        <h2>{title}</h2>
        <p className="delete-modal-message">{message}</p>
        
        <div className="delete-modal-actions">
          <button 
            className="delete-modal-cancel" 
            onClick={onClose}
          >
            Cancelar
          </button>
          <button 
            className="delete-modal-confirm" 
            onClick={() => {
              onConfirm();
              onClose();
            }}
          >
            Excluir
          </button>
        </div>
      </div>
    </div>
  );
};

export default ModalConfirmDelete;
