import React, { useState, ChangeEvent, FormEvent } from 'react';
import './ModalCreateAd.style.css';

type ModalCreateAdProps = {
  isOpen: boolean;
  onClose: () => void;
  onAddCard: (card: { titulo: string; descricao: string; preco: string; imagemFile: File }) => Promise<void>;
};

type FormData = {
  titulo: string;
  descricao: string;
  preco: string;
  imagemFile: File | null;
};

export default function ModalCreateAd({ isOpen, onClose, onAddCard }: ModalCreateAdProps) {
  const [formData, setFormData] = useState<FormData>({ titulo: '', descricao: '', preco: '', imagemFile: null });
  const [imagePreview, setImagePreview] = useState<string | null>(null);
  const [loading, setLoading] = useState<boolean>(false);
  const [error, setError] = useState<string>('');

  if (!isOpen) return null;

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
      if (!formData.imagemFile) {
        setError('Selecione um arquivo de imagem.');
        return;
      }
      
      const { titulo, descricao, preco, imagemFile } = formData;
      if (!imagemFile) throw new Error('Arquivo de imagem inválido');
      
      await onAddCard({ titulo, descricao, preco, imagemFile });
      
      // Limpar formulário
      setFormData({ titulo: '', descricao: '', preco: '', imagemFile: null });
      setImagePreview(null);
      
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

  return (
    <div className="modal-overlay">
      <div className="modal-content">
        <h2>Criar Novo Anúncio</h2>
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
            <label className="FieldName">Imagem</label>
            <input
              type="file"
              accept="image/*"
              onChange={handleFileChange}
              required
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
              onClick={onClose}
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
              {loading ? 'Salvando...' : 'Criar Anúncio'}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}
