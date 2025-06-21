import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../../contexts/AuthContext';
import '../shared/AuthForm.style.css';

type FormValues = {
    nome: string;
    email: string;
    senha: string;
};

const RegisterForm: React.FC = () => {
    const [formValues, setFormValues] = useState<FormValues>({
        nome: '',
        email: '',
        senha: '',
    });
    const navigate = useNavigate();
    const { register, loading, error, clearError } = useAuth();

    const handleSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        // Clear previous errors
        clearError();

        if (!formValues.nome.trim() || !formValues.email.trim() || !formValues.senha.trim()) {
            alert('Todos os campos são obrigatórios');
            return;
        }

        try {
            const success = await register({
                name: formValues.nome,
                email: formValues.email,
                password: formValues.senha,
                role: 'user',
            });
            if (success) {
                // Reset form
                setFormValues({ nome: '', email: '', senha: '' });
                navigate('/login');
            }
        } catch (err) {
            console.error('Registration error:', err);
        }
    };

    return (
        <form className="auth-form" onSubmit={handleSubmit}>
            <h2 className="form-title">Criar uma conta</h2>

            {/* Display auth error */}
            {error && <div className="error-message">{error}</div>}

            <div className="form-field">
                <input
                    className="form-input"
                    type="text"
                    placeholder="Nome completo"
                    value={formValues.nome}
                    onChange={(e) => {
                        setFormValues({ ...formValues, nome: e.target.value });
                        if (error) clearError();
                    }}
                    disabled={loading}
                />
            </div>

            <div className="form-field">
                <input
                    className="form-input"
                    type="email"
                    placeholder="Email"
                    value={formValues.email}
                    onChange={(e) => {
                        setFormValues({ ...formValues, email: e.target.value });
                        if (error) clearError();
                    }}
                    disabled={loading}
                />
            </div>

            <div className="form-field">
                <input
                    className="form-input"
                    type="password"
                    placeholder="Senha"
                    value={formValues.senha}
                    onChange={(e) => {
                        setFormValues({ ...formValues, senha: e.target.value });
                        if (error) clearError();
                    }}
                    disabled={loading}
                />
            </div>

            <button className="form-button" type="submit" disabled={loading}>
                {loading ? 'Registrando...' : 'Registrar'}
            </button>
        </form>
    );
};

export default RegisterForm;
