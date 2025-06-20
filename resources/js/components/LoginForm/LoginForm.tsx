import React, { useState } from "react";
import { useNavigate } from "react-router-dom";
import { useAuth } from "../../contexts/AuthContext";
import "./loginform.style.css";

type LoginFormValues = {
  email: string;
  password: string;
};

const LoginForm: React.FC = () => {
  const { login, loading, error, clearError } = useAuth();
  const navigate = useNavigate();

  const [formValues, setFormValues] = useState<LoginFormValues>({
    email: "",
    password: "",
  });

  const [validationErrors, setValidationErrors] = useState<Partial<LoginFormValues>>({});

  const validateForm = (): boolean => {
    const errors: Partial<LoginFormValues> = {};

    if (!formValues.email.trim()) {
      errors.email = "Email é obrigatório";
    } else if (!/\S+@\S+\.\S+/.test(formValues.email)) {
      errors.email = "Email inválido";
    }

    if (!formValues.password.trim()) {
      errors.password = "Senha é obrigatória";
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
    }

    try {
      const success = await login(formValues);
      
      if (success) {
        console.log("Login realizado com sucesso!");
        setFormValues({ email: "", password: "" });
        navigate("/dashboard");
      }
      // Error is handled by the useAuth hook and displayed via error state
    } catch (err) {
      console.error("Login error:", err);
    }
  };

  const handleInputChange = (field: keyof LoginFormValues, value: string) => {
    setFormValues({ ...formValues, [field]: value });
    
    // Clear validation error for this field when user starts typing
    if (validationErrors[field]) {
      setValidationErrors({ ...validationErrors, [field]: undefined });
    }
    
    // Clear general error when user starts typing
    if (error) {
      clearError();
    }
  };

  return (
    <form className="login-form" onSubmit={handleSubmit}>
      <h2 className="form-title">Entrar no Trabvirso</h2>

      {error && (
        <div className="error-message" style={{ color: 'red', marginBottom: '1rem', textAlign: 'center' }}>
          {error}
        </div>
      )}

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
          <span className="field-error" style={{ color: 'red', fontSize: '0.8rem' }}>
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
          <span className="field-error" style={{ color: 'red', fontSize: '0.8rem' }}>
            {validationErrors.password}
          </span>
        )}
      </div>

      <button 
        className="header-button" 
        type="submit"
        disabled={loading}
      >
        {loading ? 'Entrando...' : 'Entrar'}
      </button>
      
      <div className="form-links" style={{ textAlign: 'center', marginTop: '1rem' }}>
        <p>
          Não tem uma conta?{' '}
          <button 
            type="button" 
            onClick={() => navigate('/register')}
            style={{ background: 'none', border: 'none', color: 'blue', textDecoration: 'underline', cursor: 'pointer' }}
          >
            Registre-se
          </button>
        </p>
      </div>
    </form>
  );
};

export default LoginForm;
