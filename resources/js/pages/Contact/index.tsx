import React from "react";
import "./contact.style.css";

const Contact: React.FC = () => {
  return (
    <>
      <main className="contact-container">
        <section className="contact-content">
          <h1 className="contact-title">Contato</h1>
          <p className="contact-text">
            Precisa falar com a gente? Estamos aqui para te ajudar! Nosso time está disponível para tirar dúvidas, ouvir sugestões e resolver qualquer pepino que aparecer.
          </p>
          <p className="contact-text">
            Você pode nos contatar através do e-mail: <strong>contato@domusapp.com</strong> ou pelo telefone: <strong>(62) 99999-9999</strong>. Também estamos no WhatsApp para aquele papo rápido e direto.
          </p>
          <p className="contact-text">
            Nosso horário de atendimento é de segunda a sexta, das 8h às 18h. Fora desse horário, pode mandar mensagem que responderemos o mais breve possível!
          </p>
        </section>
      </main>
    </>
  );
};

export default Contact;
