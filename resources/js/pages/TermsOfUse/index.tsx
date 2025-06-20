import React from "react";
import "./terms.style.css";

const TermsOfUse: React.FC = () => {
  return (
    <>
      <main className="terms-container">
        <section className="terms-content">
          <h1 className="terms-title">Termos de Uso</h1>
          <p className="terms-text">
            Ao utilizar nosso serviço, você concorda com os termos descritos aqui. Nosso objetivo é fornecer uma plataforma segura, prática e eficiente para compra, venda e aluguel de imóveis.
          </p>
          <p className="terms-text">
            É proibido utilizar o sistema para fins ilícitos ou que violem os direitos de terceiros. Todo conteúdo disponibilizado deve ser verdadeiro e de responsabilidade do usuário.
          </p>
          <p className="terms-text">
            Podemos atualizar estes termos periodicamente, então é importante revisá-los regularmente. O uso contínuo da plataforma após mudanças implica aceitação dos novos termos.
          </p>
        </section>
      </main>
    </>
  );
};

export default TermsOfUse;
