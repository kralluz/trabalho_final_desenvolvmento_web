import { useNavigate } from "react-router-dom";
import LoginForm from "../../components/LoginForm/LoginForm";


const LoginPage: React.FC = () =>{

    const navigate = useNavigate();
    const handleNavigate = () => {
      navigate("/register");
    };
    return (
        <>
        <div> 
            <h1>Iniciar Sessão</h1>
            <LoginForm/>
            <div>
            <span onClick={handleNavigate}>Crie sua conta</span>
            </div>
        </div>
        </>
    )
}

export default LoginPage;