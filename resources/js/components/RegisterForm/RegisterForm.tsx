import React, { useState } from "react";
import { useNavigate } from "react-router-dom";
import { useAuth } from "../../contexts/AuthContext";
import "../shared/AuthForm.style.css";

type RegisterFormValues = {
  name: string;
  email: string;
  password: string;
  confirmPassword: string;
  role: 'user' | 'admin';
};

const RegisterForm: React.FC = () => {
  const { register, loading, error, clearError } = useAuth();
  const navigate = useNavigate();

  const [formValues, setFormValues] = useState<RegisterFormValues>({
    name: "",
    email: "",
    password: "",
    confirmPassword: "",
    role: "user",
  });

  const [validationErrors, setValidationErrors] = useState<Partial<RegisterFormValues>>({});
  const [successMessage, setSuccessMessage] = useState<string | null>(null);

  const validateForm = (): boolean => {
    const errors: Partial<RegisterFormValues> = {};

    if (!formValues.name.trim()) {
      errors.name = "Nome é obrigatório";
    } else if (formValues.name.length < 2) {
      errors.name = "Nome deve ter pelo menos 2 caracteres";
    }

    if (!formValues.email.trim()) {
      errors.email = "Email é obrigatório";
    } else if (!/\S+@\S+\.\S+/.test(formValues.email)) {
      errors.email = "Email inválido";
    }

    if (!formValues.password.trim()) {
      errors.password = "Senha é obrigatória";
    } 

    if (!formValues.confirmPassword.trim()) {
      errors.confirmPassword = "Confirmação de senha é obrigatória";
    } else if (formValues.password !== formValues.confirmPassword) {
      errors.confirmPassword = "Senhas não coincidem";
    }

    setValidationErrors(errors);
    return Object.keys(errors).length === 0;
  };
  const handleSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();

    // Clear previous errors
    clearError();
    setValidationErrors({});

    if (!validateForm()) {
      return;
    }    try {
      const { confirmPassword: _unused, ...registerData } = formValues;
      void _unused; // Evitar warning de variável não utilizada
      const success = await register(registerData);
        if (success) {
        console.log("Registro realizado com sucesso!");
        setSuccessMessage("Registro realizado com sucesso! Redirecionando para o login...");
        setFormValues({ 
          name: "", 
          email: "", 
          password: "", 
          confirmPassword: "", 
          role: "user" 
        });
        
        // Redirect after a short delay to show success message
        setTimeout(() => {
          navigate("/login");
        }, 2000);
      }
      // Error is handled by the useAuth hook and displayed via error state
    } catch (err) {
      console.error("Register error:", err);
      // Additional error handling could be added here if needed
    }
  };
  const handleInputChange = (field: keyof RegisterFormValues, value: string) => {
    setFormValues({ ...formValues, [field]: value });
    
    // Clear validation error for this field when user starts typing
    if (validationErrors[field]) {
      setValidationErrors({ ...validationErrors, [field]: undefined });
    }
    
    // Clear general error when user starts typing
    if (error) {
      clearError();
    }

    // Clear success message when user starts typing
    if (successMessage) {
      setSuccessMessage(null);
    }
  };
  return (
    <form className="auth-form" onSubmit={handleSubmit}>
      <h2 className="form-title">Registrar no Trabvirso</h2>
      
      {error && (
        <div className="error-message">
          {error}
        </div>
      )}

      {successMessage && (
        <div className="success-message">
          {successMessage}
        </div>
      )}

      <div className="form-field">
        <input
          className={`form-input ${validationErrors.name ? 'error' : ''}`}
          type="text"
          placeholder="Nome completo"
          value={formValues.name}
          onChange={(e) => handleInputChange('name', e.target.value)}
          disabled={loading}
        />
        {validationErrors.name && (
          <span className="field-error">
            {validationErrors.name}
          </span>
        )}
      </div>

      <div className="form-field">
        <input
          className={`form-input ${validationErrors.email ? 'error' : ''}`}
          type="email"
          placeholder="Email"
          value={formValues.email}
          onChange={(e) => handleInputChange('email', e.target.value)}
          disabled={loading}
        />
        {validationErrors.email && (
          <span className="field-error">
            {validationErrors.email}
          </span>
        )}
      </div>

      <div className="form-field">
        <input
          className={`form-input ${validationErrors.password ? 'error' : ''}`}
          type="password"
          placeholder="Senha"
          value={formValues.password}
          onChange={(e) => handleInputChange('password', e.target.value)}
          disabled={loading}
        />
        {validationErrors.password && (
          <span className="field-error">
            {validationErrors.password}
          </span>
        )}
      </div>

      <div className="form-field">
        <input
          className={`form-input ${validationErrors.confirmPassword ? 'error' : ''}`}
          type="password"
          placeholder="Confirmar senha"
          value={formValues.confirmPassword}
          onChange={(e) => handleInputChange('confirmPassword', e.target.value)}
          disabled={loading}
        />
        {validationErrors.confirmPassword && (
          <span className="field-error">
            {validationErrors.confirmPassword}
          </span>
        )}
      </div>

      <div className="form-field">
        <select
          className="form-input"
          value={formValues.role}
          onChange={(e) => handleInputChange('role', e.target.value as 'user' | 'admin')}
          disabled={loading}
        >
          <option value="user">Usuário</option>
          <option value="admin">Administrador</option>
        </select>
      </div>

      <button 
        className="form-button" 
        type="submit"
        disabled={loading}
      >
        {loading ? 'Registrando...' : 'Registrar'}
      </button>
      
      <div className="form-links">
        <p>
          Já tem uma conta?{' '}
          <button 
            type="button" 
            className="form-link-button"
            onClick={() => navigate('/login')}
          >
            Faça login
          </button>
        </p>
      </div>
    </form>
  );
};

export default RegisterForm;
