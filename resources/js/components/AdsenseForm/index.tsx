import React, { useState, useEffect } from "react";
import { CreateAdsenseRequest, Adsense } from "../../services/api";
import "./adsenseForm.style.css";

interface AdsenseFormProps {
  onSubmit: (data: CreateAdsenseRequest) => Promise<boolean>;
  onCancel: () => void;
  initialData?: Adsense | null;
  isLoading?: boolean;
}

const AdsenseForm: React.FC<AdsenseFormProps> = ({
  onSubmit,
  onCancel,
  initialData,
  isLoading = false,
}) => {
  const [formData, setFormData] = useState<CreateAdsenseRequest>({
    title: "",
    description: "",
    price: 0,
  });

  const [errors, setErrors] = useState<Record<string, string>>({});

  useEffect(() => {
    if (initialData) {
      setFormData({
        title: initialData.title,
        description: initialData.description,
        price: initialData.price,
      });
    }
  }, [initialData]);
  const validateForm = (): boolean => {
    const newErrors: Record<string, string> = {};

    if (!formData.title.trim()) {
      newErrors.title = "Título é obrigatório";
    }

    if (!formData.description.trim()) {
      newErrors.description = "Descrição é obrigatória";
    }

    if (formData.price <= 0) {
      newErrors.price = "Preço deve ser maior que zero";
    }

    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    
    if (!validateForm()) {
      return;
    }

    const success = await onSubmit(formData);
    if (success) {
      // Reset form if it's a new adsense (not editing)
      if (!initialData) {
        setFormData({ title: "", description: "", price: 0 });
      }
    }
  };

  const handleChange = (
    e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>
  ) => {
    const { name, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: name === "price" ? parseFloat(value) || 0 : value,
    }));    // Clear error when user starts typing
    if (errors[name as keyof CreateAdsenseRequest]) {
      setErrors(prev => {
        const newErrors = { ...prev };
        delete newErrors[name];
        return newErrors;
      });
    }
  };

  return (
    <div className="adsense-form-container">
      <div className="adsense-form-header">
        <h2>{initialData ? "Editar Anúncio" : "Criar Novo Anúncio"}</h2>
      </div>

      <form onSubmit={handleSubmit} className="adsense-form">
        <div className="form-group">
          <label htmlFor="title" className="form-label">
            Título *
          </label>
          <input
            type="text"
            id="title"
            name="title"
            value={formData.title}
            onChange={handleChange}
            className={`form-input ${errors.title ? "error" : ""}`}
            placeholder="Digite o título do anúncio"
            disabled={isLoading}
          />
          {errors.title && <span className="error-message">{errors.title}</span>}
        </div>

        <div className="form-group">
          <label htmlFor="description" className="form-label">
            Descrição *
          </label>
          <textarea
            id="description"
            name="description"
            value={formData.description}
            onChange={handleChange}
            className={`form-textarea ${errors.description ? "error" : ""}`}
            placeholder="Descreva seu anúncio"
            rows={4}
            disabled={isLoading}
          />
          {errors.description && (
            <span className="error-message">{errors.description}</span>
          )}
        </div>

        <div className="form-group">
          <label htmlFor="price" className="form-label">
            Preço (R$) *
          </label>
          <input
            type="number"
            id="price"
            name="price"
            value={formData.price}
            onChange={handleChange}
            className={`form-input ${errors.price ? "error" : ""}`}
            placeholder="0.00"
            min="0"
            step="0.01"
            disabled={isLoading}
          />
          {errors.price && <span className="error-message">{errors.price}</span>}
        </div>

        <div className="form-actions">
          <button
            type="button"
            onClick={onCancel}
            className="btn btn-secondary"
            disabled={isLoading}
          >
            Cancelar
          </button>
          <button
            type="submit"
            className="btn btn-primary"
            disabled={isLoading}
          >
            {isLoading ? "Salvando..." : initialData ? "Atualizar" : "Criar"}
          </button>
        </div>
      </form>
    </div>
  );
};

export default AdsenseForm;
