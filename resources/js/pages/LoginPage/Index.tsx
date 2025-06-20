import { useNavigate } from 'react-router-dom';
import LoginForm from '../../components/LoginForm/LoginForm';
import './LoginPage.style.css';

const LoginPage: React.FC = () => {
    const navigate = useNavigate();

    return (
        <div className="auth-container">
            <div className="auth-card">
                <div className="auth-header">
                    <h1 className="auth-title">Iniciar Sessão</h1>
                    <p className="auth-subtitle">Acesse sua conta para gerenciar seus anúncios</p>
                </div>

                <LoginForm />

                <div className="auth-footer">
                    Ainda não tem uma conta?{' '}
                    <span className="auth-link" onClick={() => navigate('/register')}>
                        Criar conta
                    </span>
                </div>
            </div>
        </div>
    );
};

export default LoginPage;
