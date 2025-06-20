import React from "react";
import "./privacy.style.css";

const PrivacyPolicy: React.FC = () => {
  return (
    <>
      <main className="privacy-container">
        <section className="privacy-content">
          <h1 className="privacy-title">Política de Privacidade</h1>
          <p className="privacy-text">
            Sua privacidade é muito importante pra gente. Coletamos apenas os dados essenciais para oferecer uma experiência personalizada e segura.
          </p>
          <p className="privacy-text">
            As informações fornecidas não serão compartilhadas com terceiros sem seu consentimento, exceto quando exigido por lei.
          </p>
          <p className="privacy-text">
            Você pode solicitar a exclusão dos seus dados a qualquer momento, basta entrar em contato conosco pelos nossos canais de atendimento.
          </p>
        </section>
      </main>
    </>
  );
};

export default PrivacyPolicy;
