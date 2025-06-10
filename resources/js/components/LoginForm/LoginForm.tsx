import React, { useState } from "react";
import { useNavigate } from "react-router-dom";
import { useAuth } from "../../contexts/AuthContext";
import "./loginform.style.css"; // Crie esse arquivo com o CSS abaixo

type LoginFormValues = {
  email: string;
  password: string;
};

const LoginForm: React.FC = () => {
  const { login } = useAuth();

  const [formValues, setFormValues] = useState<LoginFormValues>({
    email: "",
    password: "",
  });

  const navigate = useNavigate();

  const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();

    if (!formValues.email || !formValues.password) {
      alert("Todos os campos são obrigatórios");
      return;
    }

    login(formValues);

    console.log("Login realizado com:", JSON.stringify(formValues, null, 2));

    setFormValues({
      email: "",
      password: "",
    });

    navigate("/dashboard");
  };

  return (
    <form className="login-form" onSubmit={handleSubmit}>
      <h2 className="form-title">Entrar no Domus</h2>

      <input
        className="form-input"
        type="email"
        placeholder="Email"
        value={formValues.email}
        onChange={(e) =>
          setFormValues({ ...formValues, email: e.target.value })
        }
      />

      <input
        className="form-input"
        type="password"
        placeholder="Senha"
        value={formValues.password}
        onChange={(e) =>
          setFormValues({ ...formValues, password: e.target.value })
        }
      />

      <button className="header-button" type="submit">
        Entrar
      </button>
    </form>
  );
};

export default LoginForm;
