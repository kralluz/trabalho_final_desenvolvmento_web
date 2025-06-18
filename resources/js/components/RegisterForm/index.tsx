import React, { useState } from "react";
import { useNavigate } from "react-router-dom";
import { apiService, RegisterRequest } from "../../services/api";
import "./registerform.style.css";

type FormValues = {
  name: string;
  email: string;
  password: string;
};

const RegisterForm: React.FC = () => {
  const [formValues, setFormValues] = useState<FormValues>({
    name: "",
    email: "",
    password: "",
  });

  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const navigate = useNavigate();

  const handleSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    setError(null);

    if (!formValues.name || !formValues.email || !formValues.password) {
      setError("Todos os campos são obrigatórios");
      return;
    }

    if (formValues.password.length < 6) {
      setError("A senha deve ter pelo menos 6 caracteres");
      return;
    }

    setLoading(true);

    try {
      const registerData: RegisterRequest = {
        name: formValues.name,
        email: formValues.email,
        password: formValues.password,
        role: "user", // Padrão como usuário comum
      };

      const response = await apiService.register(registerData);

      if (response.success) {
        alert(`Usuário criado com sucesso! Bem-vindo, ${response.data?.user.name}!`);
        
        // Limpar formulário
        setFormValues({
          name: "",
          email: "",
          password: "",
        });

        // Navegar para login
        navigate("/login");
      } else {
        setError(response.message || "Erro ao criar usuário");
      }
    } catch (error: any) {
      console.error("Erro no registro:", error);
      setError(error.message || "Erro interno do servidor");
    } finally {
      setLoading(false);
    }
  };

  return (
    <form className="register-form" onSubmit={handleSubmit}>
      <h2 className="form-title">Criar uma conta</h2>

      {error && (
        <div style={{ color: 'red', marginBottom: '10px', textAlign: 'center' }}>
          {error}
        </div>
      )}

      <input
        className="form-input"
        type="text"
        placeholder="Nome completo"
        value={formValues.name}
        onChange={(e) => setFormValues({ ...formValues, name: e.target.value })}
        disabled={loading}
      />

      <input
        className="form-input"
        type="email"
        placeholder="Email"
        value={formValues.email}
        onChange={(e) =>
          setFormValues({ ...formValues, email: e.target.value })
        }
        disabled={loading}
      />

      <input
        className="form-input"
        type="password"
        placeholder="Senha (mínimo 6 caracteres)"
        value={formValues.password}
        onChange={(e) =>
          setFormValues({ ...formValues, password: e.target.value })
        }
        disabled={loading}
      />

      <button 
        className="header-button" 
        type="submit"
        disabled={loading}
        style={{ 
          opacity: loading ? 0.6 : 1,
          cursor: loading ? 'not-allowed' : 'pointer'
        }}
      >
        {loading ? 'Registrando...' : 'Registrar'}
      </button>
    </form>
  );
};

export default RegisterForm;
