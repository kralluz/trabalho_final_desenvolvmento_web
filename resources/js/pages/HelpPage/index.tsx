import React from 'react';
import '../InfoPages.style.css';

const HelpPage: React.FC = () => {
  return (
    <div className="info-container">
      <div className="info-content">
        <h1>Central de Ajuda</h1>
        
        <section className="info-section">
          <h2>Perguntas Frequentes</h2>
          
          <div className="faq-item">
            <h3>Como criar um anúncio?</h3>
            <p>Para criar um anúncio, siga estes passos:</p>
            <ol>
              <li>Faça login na sua conta</li>
              <li>Acesse "Meus Anúncios"</li>
              <li>Clique em "Novo Anúncio"</li>
              <li>Preencha as informações necessárias</li>
              <li>Adicione fotos do seu produto</li>
              <li>Clique em "Publicar"</li>
            </ol>
          </div>

          <div className="faq-item">
            <h3>Como editar meu perfil?</h3>
            <p>Você pode editar suas informações de perfil seguindo estes passos:</p>
            <ol>
              <li>Clique no botão "Perfil" no cabeçalho</li>
              <li>Atualize suas informações</li>
              <li>Salve as alterações</li>
            </ol>
          </div>

          <div className="faq-item">
            <h3>Como entrar em contato com um vendedor?</h3>
            <p>Ao visualizar um anúncio, você encontrará as informações de contato do vendedor. Utilize o método de contato disponibilizado para fazer suas perguntas ou negociar.</p>
          </div>
        </section>

        <section className="info-section">
          <h2>Suporte</h2>
          <p>Se você não encontrou a resposta para sua dúvida, entre em contato com nosso suporte:</p>
          <ul className="support-channels">
            <li>E-mail: suporte@trabvirso.com</li>
            <li>Telefone: (11) 4002-8922</li>
            <li>Horário de atendimento: Segunda a Sexta, das 9h às 18h</li>
          </ul>
        </section>
      </div>
    </div>
  );
};

export default HelpPage;
