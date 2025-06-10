import React, { useState } from "react";
import { useNavigate } from "react-router-dom";
import "./registerform.style.css"; // Arquivo de CSS novo!

type FormValues = {
  nome: string;
  email: string;
  senha: string;
};

const RegisterForm: React.FC = () => {
  const [formValues, setFormValues] = useState<FormValues>({
    nome: "",
    email: "",
    senha: "",
  });

  const navigate = useNavigate();

  const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();

    if (!formValues.nome || !formValues.email || !formValues.senha) {
      alert("Todos os campos são obrigatórios");
      return;
    }

    console.log(JSON.stringify(formValues, null, 2));

    setFormValues({
      nome: "",
      email: "",
      senha: "",
    });

    navigate("/login");
  };

  return (
    <form className="register-form" onSubmit={handleSubmit}>
      <h2 className="form-title">Criar uma conta</h2>

      <input
        className="form-input"
        type="text"
        placeholder="Nome completo"
        value={formValues.nome}
        onChange={(e) => setFormValues({ ...formValues, nome: e.target.value })}
      />

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
        value={formValues.senha}
        onChange={(e) =>
          setFormValues({ ...formValues, senha: e.target.value })
        }
      />

      <button className="header-button" type="submit">
        Registrar
      </button>
    </form>
  );
};

export default RegisterForm;
