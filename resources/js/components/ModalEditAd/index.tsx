import React, { useState, useEffect, ChangeEvent, FormEvent } from 'react';
import './ModalEditAd.style.css';
import { CardItem } from '../PostList';

type ModalEditAdProps = {
  isOpen: boolean;
  onClose: () => void;
  onUpdateCard: (id: number, data: { titulo: string; descricao: string; preco: string; imagemFile?: File }) => Promise<void>;
  cardData: CardItem | null;
};

type FormData = {
  titulo: string;
  descricao: string;
  preco: string;
  imagemFile: File | null;
};

export default function ModalEditAd({ isOpen, onClose, onUpdateCard, cardData }: ModalEditAdProps) {
  const [formData, setFormData] = useState<FormData>({ titulo: '', descricao: '', preco: '', imagemFile: null });
  const [imagePreview, setImagePreview] = useState<string | null>(null);
  const [loading, setLoading] = useState<boolean>(false);
  const [error, setError] = useState<string>('');

  // Preencher formulário quando cardData mudar
  useEffect(() => {
    if (cardData) {
      setFormData({
        titulo: cardData.titulo,
        descricao: cardData.descricao,
        preco: cardData.preco,
        imagemFile: null
      });
      setImagePreview(cardData.imagem);
    }
  }, [cardData]);

  if (!isOpen || !cardData) return null;

  const handleChange = (e: ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleFileChange = (e: ChangeEvent<HTMLInputElement>) => {
    if (e.target.files && e.target.files[0]) {
      const file = e.target.files[0];
      setFormData({ ...formData, imagemFile: file });
      
      // Criar preview da imagem
      const reader = new FileReader();
      reader.onloadend = () => {
        setImagePreview(reader.result as string);
      };
      reader.readAsDataURL(file);
    }
  };

  const handleSubmit = async (e: FormEvent) => {
    e.preventDefault();
    setLoading(true);
    setError('');

    try {
      const { titulo, descricao, preco, imagemFile } = formData;
      
      const updateData: { titulo: string; descricao: string; preco: string; imagemFile?: File } = {
        titulo,
        descricao,
        preco
      };

      if (imagemFile) {
        updateData.imagemFile = imagemFile;
      }
      
      await onUpdateCard(cardData.id, updateData);
      
      onClose(); // Fecha o modal após sucesso
    } catch (err) {
      if (err instanceof Error) {
        setError(err.message);
      } else {
        setError('Erro desconhecido.');
      }
    } finally {
      setLoading(false);
    }
  };

  const handleClose = () => {
    setFormData({ titulo: '', descricao: '', preco: '', imagemFile: null });
    setImagePreview(null);
    setError('');
    onClose();
  };

  return (
    <div className="modal-overlay">
      <div className="modal-content">
        <h2>Editar Anúncio</h2>
        {error && <div className="error-message">{error}</div>}
        
        <form onSubmit={handleSubmit}>
          <div className="input-group">
            <label className="FieldName">Título</label>
            <input
              type="text"
              name="titulo"
              value={formData.titulo}
              onChange={handleChange}
              required
              disabled={loading}
            />
          </div>

          <div className="input-group">
            <label className="FieldName">Descrição</label>
            <textarea
              name="descricao"
              value={formData.descricao}
              onChange={handleChange}
              required
              disabled={loading}
            />
          </div>

          <div className="input-group">
            <label className="FieldName">Preço</label>
            <input
              type="text"
              name="preco"
              value={formData.preco}
              onChange={handleChange}
              required
              disabled={loading}
              placeholder="R$ 0,00"
            />
          </div>

          <div className="input-group">
            <label className="FieldName">Nova Imagem (opcional)</label>
            <input
              type="file"
              accept="image/*"
              onChange={handleFileChange}
              disabled={loading}
            />
            {imagePreview && (
              <div className="image-preview">
                <img 
                  src={imagePreview} 
                  alt="Preview"
                  style={{ maxWidth: '200px', marginTop: '10px' }}
                />
              </div>
            )}
          </div>

          <div className="modal-actions">
            <button 
              type="button" 
              onClick={handleClose}
              className="cancel-button"
              disabled={loading}
            >
              Cancelar
            </button>
            <button 
              type="submit" 
              className="submit-button"
              disabled={loading}
            >
              {loading ? 'Salvando...' : 'Atualizar Anúncio'}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}
