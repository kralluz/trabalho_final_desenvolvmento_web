import React from "react";
import "./aboutus.style.css";

const AboutUs: React.FC = () => {
  return (
    <>
      <main className="aboutus-container">
        <section className="aboutus-content">
          <h1 className="aboutus-title">Sobre Nós</h1>
          <p className="aboutus-text">
            Bem-vindo ao nosso projeto! Somos uma equipe dedicada a criar soluções inovadoras no mercado de imóveis. 
            Nosso objetivo é simplificar o processo de compra, venda e aluguel de imóveis com tecnologia, design e praticidade.
          </p>
          <p className="aboutus-text">
            Prezamos pela transparência, facilidade de uso e um atendimento de qualidade. Aqui, você encontra seu imóvel com rapidez e segurança.
          </p>
          <p className="aboutus-text">
            Obrigado por confiar na gente! Vamos juntos realizar seu próximo sonho!
          </p>
        </section>
      </main>
    </>
  );
};

export default AboutUs;