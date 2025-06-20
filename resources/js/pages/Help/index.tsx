import React from "react";
import { Link } from "react-router-dom";
import "./help.style.css";

const Help: React.FC = () => {
  return (
    <main className="help-container">
      <div className="help-content">
        <h1>Central de Ajuda</h1>
        
        <section className="help-section">
          <h2>Perguntas Frequentes</h2>
          
          <div className="faq-item">
            <h3>Como criar uma conta?</h3>
            <p>Para criar uma conta, clique em "Registrar" no menu superior. Preencha seus dados pessoais e siga as instruções. Após confirmar seu e-mail, você poderá acessar todas as funcionalidades da plataforma.</p>
          </div>
          
          <div className="faq-item">
            <h3>Como publicar um anúncio?</h3>
            <p>Após fazer login, acesse o Dashboard e clique no botão "Criar Anúncio". Preencha as informações do seu produto, adicione imagens e publique. Seu anúncio será revisado e publicado em breve.</p>
          </div>
          
          <div className="faq-item">
            <h3>Como editar meu perfil?</h3>
            <p>No menu superior, clique no seu nome/avatar para abrir as opções de usuário. Selecione "Editar Perfil" para atualizar suas informações pessoais, foto e preferências.</p>
          </div>
          
          <div className="faq-item">
            <h3>Como gerenciar meus anúncios?</h3>
            <p>No Dashboard, você encontrará todos os seus anúncios listados. Cada card possui opções para editar ou excluir o anúncio. As alterações são refletidas instantaneamente na plataforma.</p>
          </div>
        </section>

        <section className="help-section">
          <h2>Links Úteis</h2>
          <div className="help-links">
            <Link to="/privacyPolicy" className="link-button">Política de Privacidade</Link>
            <Link to="/termsOfUse" className="link-button">Termos de Uso</Link>
            <Link to="/contact" className="link-button">Contato</Link>
          </div>
        </section>

        <section className="help-section">
          <h2>Suporte</h2>
          <p>Não encontrou o que procurava? Entre em contato conosco:</p>
          <div className="help-links">
            <a href="mailto:suporte@trabvirso.com" className="link-button">E-mail: suporte@trabvirso.com</a>
            <a href="tel:+5511999999999" className="link-button">Telefone: (11) 99999-9999</a>
          </div>
        </section>

        <footer className="help-footer">
          <Link to="/" className="back-button">Voltar para a Página Inicial</Link>
        </footer>
      </div>
    </main>
  );
};

export default Help;
