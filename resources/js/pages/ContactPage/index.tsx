import React, { useState } from 'react';
import '../InfoPages.style.css';
import './ContactPage.style.css';

const ContactPage: React.FC = () => {
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    subject: '',
    message: ''
  });

  const [submitting, setSubmitting] = useState(false);
  const [submitStatus, setSubmitStatus] = useState<{
    type: 'success' | 'error' | null;
    message: string;
  }>({ type: null, message: '' });

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>) => {
    const { name, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: value
    }));
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setSubmitting(true);
    
    try {
      // Simulating API call
      await new Promise(resolve => setTimeout(resolve, 1000));
      
      setSubmitStatus({
        type: 'success',
        message: 'Mensagem enviada com sucesso! Entraremos em contato em breve.'
      });
      setFormData({
        name: '',
        email: '',
        subject: '',
        message: ''
      });
    } catch (error) {
      setSubmitStatus({
        type: 'error',
        message: 'Erro ao enviar mensagem. Por favor, tente novamente.'
      });
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <div className="info-container">
      <div className="info-content">
        <h1>Contato</h1>

        <section className="info-section contact-section">
          <div className="contact-info">
            <h2>Informações de Contato</h2>
            <p>Entre em contato conosco através do formulário abaixo ou pelos seguintes canais:</p>
            
            <div className="contact-details">
              <div className="contact-item">
                <strong>E-mail:</strong>
                <p>contato@trabvirso.com</p>
              </div>
              
              <div className="contact-item">
                <strong>Horário de Atendimento:</strong>
                <p>Segunda a Sexta: 9h às 18h</p>
              </div>
              
              <div className="contact-item">
                <strong>Endereço:</strong>
                <p>Rua Virtual, 123 - São Paulo, SP</p>
              </div>
            </div>
          </div>

          <form className="contact-form" onSubmit={handleSubmit}>
            <h2>Envie sua Mensagem</h2>
            
            {submitStatus.type && (
              <div className={`status-message ${submitStatus.type}`}>
                {submitStatus.message}
              </div>
            )}

            <div className="form-group">
              <label htmlFor="name">Nome</label>
              <input
                type="text"
                id="name"
                name="name"
                value={formData.name}
                onChange={handleChange}
                required
              />
            </div>

            <div className="form-group">
              <label htmlFor="email">E-mail</label>
              <input
                type="email"
                id="email"
                name="email"
                value={formData.email}
                onChange={handleChange}
                required
              />
            </div>

            <div className="form-group">
              <label htmlFor="subject">Assunto</label>
              <select
                id="subject"
                name="subject"
                value={formData.subject}
                onChange={handleChange}
                required
              >
                <option value="">Selecione um assunto</option>
                <option value="suporte">Suporte Técnico</option>
                <option value="sugestao">Sugestão</option>
                <option value="reclamacao">Reclamação</option>
                <option value="parceria">Parceria</option>
                <option value="outro">Outro</option>
              </select>
            </div>

            <div className="form-group">
              <label htmlFor="message">Mensagem</label>
              <textarea
                id="message"
                name="message"
                value={formData.message}
                onChange={handleChange}
                required
                rows={5}
              />
            </div>

            <button
              type="submit"
              className="submit-button"
              disabled={submitting}
            >
              {submitting ? 'Enviando...' : 'Enviar Mensagem'}
            </button>
          </form>
        </section>
      </div>
    </div>
  );
};

export default ContactPage;
