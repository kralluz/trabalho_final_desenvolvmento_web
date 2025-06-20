import React, { useState, useEffect } from "react";
import { CreateAdsenseRequest, Adsense } from "../../types";
import cloudinaryService from "../../services/cloudinaryService";
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

  const [selectedImage, setSelectedImage] = useState<File | null>(null);
  const [imagePreview, setImagePreview] = useState<string | null>(null);
  const [uploadError, setUploadError] = useState<string | null>(null);
  const [isUploading, setIsUploading] = useState(false);
  const [errors, setErrors] = useState<Record<string, string>>({});

  useEffect(() => {
    if (initialData) {
      setFormData({
        title: initialData.title,
        description: initialData.description,
        price: initialData.price,
      });
      if (initialData.images?.[0]?.url) {
        setImagePreview(initialData.images[0].url);
      }
    }
  }, [initialData]);

  const validateImage = (file: File): boolean => {
    // Validate file size (5MB)
    if (file.size > 5 * 1024 * 1024) {
      setUploadError("A imagem deve ter no máximo 5MB");
      return false;
    }

    // Validate file type
    const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!allowedTypes.includes(file.type)) {
      setUploadError("Apenas imagens JPG, PNG e GIF são permitidas");
      return false;
    }

    return true;
  };

  const handleImageChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (file) {
      if (validateImage(file)) {
        setSelectedImage(file);
        setImagePreview(URL.createObjectURL(file));
        setUploadError(null);
      }
    }
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setErrors({});
    setUploadError(null);

    if (!validateForm()) {
      return;
    }

    try {
      setIsUploading(true);
      let imageUrl = initialData?.images?.[0]?.url || "";

      if (selectedImage) {
        const uploadResult = await cloudinaryService.uploadImage(selectedImage);
        imageUrl = uploadResult.secure_url;
      }

      const success = await onSubmit({
        ...formData,
        image_url: imageUrl,
      });

      if (success && !initialData) {
        // Reset form only for new adsense
        setFormData({ title: "", description: "", price: 0 });
        setSelectedImage(null);
        setImagePreview(null);
      }
    } catch (error) {
      setUploadError("Erro ao fazer upload da imagem. Tente novamente.");
      console.error("Upload error:", error);
    } finally {
      setIsUploading(false);
    }
  };

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

    if (!selectedImage && !initialData?.images?.[0]?.url) {
      newErrors.image = "Imagem é obrigatória";
    }

    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };

  const handleChange = (
    e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>
  ) => {
    const { name, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: name === "price" ? parseFloat(value) || 0 : value,
    }));

    // Clear error when user starts typing
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
            onChange={(e) => setFormData(prev => ({ ...prev, title: e.target.value }))}
            className={`form-input ${errors.title ? "error" : ""}`}
            disabled={isLoading || isUploading}
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
            onChange={(e) => setFormData(prev => ({ ...prev, description: e.target.value }))}
            className={`form-input ${errors.description ? "error" : ""}`}
            disabled={isLoading || isUploading}
          />
          {errors.description && <span className="error-message">{errors.description}</span>}
        </div>

        <div className="form-group">
          <label htmlFor="price" className="form-label">
            Preço *
          </label>
          <input
            type="number"
            id="price"
            name="price"
            value={formData.price}
            onChange={(e) => setFormData(prev => ({ ...prev, price: Number(e.target.value) }))}
            className={`form-input ${errors.price ? "error" : ""}`}
            min="0"
            step="0.01"
            disabled={isLoading || isUploading}
          />
          {errors.price && <span className="error-message">{errors.price}</span>}
        </div>

        <div className="form-group">
          <label htmlFor="image" className="form-label">
            Imagem * {isUploading && <span className="upload-status">(Enviando...)</span>}
          </label>
          <input
            type="file"
            id="image"
            name="image"
            accept="image/jpeg,image/png,image/gif"
            onChange={handleImageChange}
            className={`form-input ${errors.image ? "error" : ""}`}
            disabled={isLoading || isUploading}
          />
          {imagePreview && (
            <div className="image-preview">
              <img src={imagePreview} alt="Preview" style={{ maxWidth: '200px', marginTop: '10px' }} />
            </div>
          )}
          {errors.image && <span className="error-message">{errors.image}</span>}
          {uploadError && <span className="error-message">{uploadError}</span>}
        </div>

        <div className="form-actions">
          <button
            type="submit"
            className="btn btn-primary"
            disabled={isLoading || isUploading}
          >
            {isLoading || isUploading ? (
              "Salvando..."
            ) : initialData ? (
              "Atualizar Anúncio"
            ) : (
              "Criar Anúncio"
            )}
          </button>
          <button
            type="button"
            className="btn btn-secondary"
            onClick={onCancel}
            disabled={isLoading || isUploading}
          >
            Cancelar
          </button>
        </div>
      </form>
    </div>
  );
};

export default AdsenseForm;