import React, { useState } from "react";
import './ModalEditUser.style.css';

interface ModalEditUserProps {
  isOpen: boolean;
  onClose: () => void;
  onSubmit: (data: {
    name: string;
    email: string;
    currentPassword?: string;
    newPassword?: string;
  }) => void;
  initialData: {
    name: string;
    email: string;
  };
}

const ModalEditUser: React.FC<ModalEditUserProps> = ({
  isOpen,
  onClose,
  onSubmit,
  initialData
}) => {
  const [formData, setFormData] = useState({
    name: initialData.name,
    email: initialData.email,
    currentPassword: '',
    newPassword: '',
  });
  const [error, setError] = useState<string>('');

  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const { name, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: value
    }));
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    
    if (!formData.name || !formData.email) {
      setError('Nome e e-mail são obrigatórios.');
      return;
    }

    // Se uma nova senha foi fornecida, a senha atual também deve ser fornecida
    if (formData.newPassword && !formData.currentPassword) {
      setError('Para alterar a senha, informe a senha atual.');
      return;
    }

    // Se a senha atual foi fornecida, a nova senha também deve ser fornecida
    if (formData.currentPassword && !formData.newPassword) {
      setError('Informe a nova senha.');
      return;
    }

    const submitData = {
      name: formData.name,
      email: formData.email,
      ...(formData.currentPassword && formData.newPassword ? {
        currentPassword: formData.currentPassword,
        newPassword: formData.newPassword
      } : {})
    };

    onSubmit(submitData);
  };

  if (!isOpen) return null;

  return (
    <div className="modal-overlay" onClick={onClose}>
      <div className="modal-content" onClick={e => e.stopPropagation()}>
        <h2>Editar Perfil</h2>
        
        {error && (
          <div className="error-message">{error}</div>
        )}

        <form onSubmit={handleSubmit}>
          <div className="input-group">
            <label>Nome</label>
            <input
              type="text"
              name="name"
              value={formData.name}
              onChange={handleChange}
              placeholder="Seu nome"
              required
            />
          </div>

          <div className="input-group">
            <label>E-mail</label>
            <input
              type="email"
              name="email"
              value={formData.email}
              onChange={handleChange}
              placeholder="seu@email.com"
              required
            />
          </div>

          <div className="password-section">
            <h3>Alterar Senha (opcional)</h3>
            
            <div className="input-group">
              <label>Senha Atual</label>
              <input
                type="password"
                name="currentPassword"
                value={formData.currentPassword}
                onChange={handleChange}
                placeholder="Digite sua senha atual"
              />
            </div>

            <div className="input-group">
              <label>Nova Senha</label>
              <input
                type="password"
                name="newPassword"
                value={formData.newPassword}
                onChange={handleChange}
                placeholder="Digite a nova senha"
                minLength={6}
              />
            </div>
          </div>

          <div className="modal-actions">
            <button
              type="button"
              onClick={onClose}
              className="cancel-button"
            >
              Cancelar
            </button>
            <button
              type="submit"
              className="submit-button"
            >
              Salvar Alterações
            </button>
          </div>
        </form>
      </div>
    </div>
  );
};

export default ModalEditUser;
