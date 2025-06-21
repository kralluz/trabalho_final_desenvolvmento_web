import { useNavigate } from 'react-router-dom';
import RegisterForm from '../../components/RegisterForm';
import '../LoginPage/LoginPage.style.css';

const RegisterPage: React.FC = () => {
    const navigate = useNavigate();
    const handleNavigate = () => {
        navigate('/login');
    };
    return (
        <div className="auth-container">
            <div className="auth-card">
                <RegisterForm />
                <div className="auth-footer">
                    Já tem uma conta?{' '}
                    <span className="auth-link" onClick={handleNavigate}>
                        Faça login
                    </span>
                </div>
            </div>
        </div>
    );
};

export default RegisterPage;
