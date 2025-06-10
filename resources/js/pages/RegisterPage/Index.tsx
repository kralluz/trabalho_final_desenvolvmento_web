import { useNavigate } from "react-router-dom";
import RegisterForm from "../../components/RegisterForm";

const RegisterPage: React.FC = () => {
  const navigate = useNavigate();
  const handleNavigate = () => {
    navigate("/login");
  };
  return (
    <>
      <div>
        <h1>Crie sua conta</h1>
        <RegisterForm />
        <div>
          <p>já tem uma conta?</p>
          <span onClick={handleNavigate}>Iniciar sessão</span>
        </div>
      </div>
    </>
  );
};

export default RegisterPage;
