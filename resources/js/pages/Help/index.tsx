import React from "react";
import "./help.style.css";

const Help: React.FC = () => {
  return (
    <>
      <main className="help-container">
        <section className="help-content">
          <h1 className="help-title">Ajuda</h1>
          <p className="help-text">
            Tá com dúvida? Relaxa, a gente te ajuda! Aqui você encontra respostas para as perguntas mais frequentes e orientações para resolver probleminhas básicos.
          </p>
          <p className="help-text">
            Não encontrou o que procurava? Chama a gente pelo WhatsApp ou mande um e-mail, nosso time vai te responder rapidinho.
          </p>
          <p className="help-text">
            Lembre-se: nossa missão é facilitar sua jornada e te deixar confortável usando nossa plataforma.
          </p>
        </section>
      </main>
    </>
  );
};

export default Help;
