import React, { useState } from 'react';
import './ModalEditAd.style.css';

interface ModalEditAdProps {
    isOpen: boolean;
    onClose: () => void;
    onSubmit: (data: { titulo: string; descricao: string; preco: string; imagemFile?: File }) => void;
    initialData: {
        titulo: string;
        descricao: string;
        preco: string;
        imagem?: string;
    };
}

const ModalEditAd: React.FC<ModalEditAdProps> = ({ isOpen, onClose, onSubmit, initialData }) => {
    const [formData, setFormData] = useState({
        titulo: initialData.titulo,
        descricao: initialData.descricao,
        preco: initialData.preco,
    });
    const [imagePreview, setImagePreview] = useState<string>(initialData.imagem || '');
    const [imageFile, setImageFile] = useState<File>();
    const [error, setError] = useState<string>('');

    const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
        const { name, value } = e.target;
        setFormData((prev) => ({
            ...prev,
            [name]: value,
        }));
    };

    const handleImageChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const file = e.target.files?.[0];
        if (file) {
            setImageFile(file);
            const reader = new FileReader();
            reader.onloadend = () => {
                setImagePreview(reader.result as string);
            };
            reader.readAsDataURL(file);
        }
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();

        if (!formData.titulo || !formData.descricao || !formData.preco) {
            setError('Por favor, preencha todos os campos obrigatórios.');
            return;
        }

        onSubmit({
            ...formData,
            imagemFile: imageFile,
        });
    };

    if (!isOpen) return null;

    return (
        <div className="modal-overlay" onClick={onClose}>
            <div className="modal-content" onClick={(e) => e.stopPropagation()}>
                <h2>Editar Anúncio</h2>

                {error && <div className="error-message">{error}</div>}

                <form onSubmit={handleSubmit}>
                    <div className="input-group">
                        <label>Título</label>
                        <input
                            type="text"
                            name="titulo"
                            value={formData.titulo}
                            onChange={handleChange}
                            placeholder="Digite o título do anúncio"
                            required
                        />
                    </div>

                    <div className="input-group">
                        <label>Descrição</label>
                        <textarea
                            style={{
                                resize: 'none',
                            }}
                            name="descricao"
                            value={formData.descricao}
                            onChange={handleChange}
                            placeholder="Digite a descrição do anúncio"
                            required
                        />
                    </div>

                    <div className="input-group">
                        <label>Preço</label>
                        <input type="text" name="preco" value={formData.preco} onChange={handleChange} placeholder="Ex: R$100.00" required />
                    </div>

                    <div className="file-input-container">
                        <input type="file" id="image" className="file-input" accept="image/*" onChange={handleImageChange} />
                        <label htmlFor="image" className="file-input-label">
                            <span className="file-input-icon">📷</span>
                            <span className="file-input-text">{imageFile ? imageFile.name : 'Escolher nova imagem'}</span>
                        </label>
                        {imagePreview && (
                            <div className="image-preview">
                                <img src={imagePreview} alt="Preview" />
                            </div>
                        )}
                    </div>

                    <div className="modal-actions">
                        <button type="button" onClick={onClose} className="cancel-button">
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            style={{
                                backgroundColor: '#4CAF50',
                                color: 'white',
                                padding: '10px 20px',
                                border: 'none',
                                borderRadius: '5px',
                                cursor: 'pointer',
                            }}
                        >
                            Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>
    );
};

export default ModalEditAd;
