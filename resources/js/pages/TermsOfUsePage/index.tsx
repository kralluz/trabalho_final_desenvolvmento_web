import React from 'react';
import '../InfoPages.style.css';

const TermsOfUsePage: React.FC = () => {
  return (
    <div className="info-container">
      <div className="info-content">
        <h1>Termos de Uso</h1>

        <section className="info-section">
          <p>Última atualização: 20 de junho de 2025</p>

          <h2>1. Aceitação dos Termos</h2>
          <p>Ao acessar e usar a Trabvirso, você concorda com estes Termos de Uso e todas as leis e regulamentos aplicáveis.</p>

          <h2>2. Uso do Serviço</h2>
          <h3>2.1 Elegibilidade</h3>
          <p>Para usar nossos serviços, você deve:</p>
          <ul>
            <li>Ter pelo menos 18 anos</li>
            <li>Fornecer informações verdadeiras e precisas</li>
            <li>Manter suas informações atualizadas</li>
          </ul>

          <h3>2.2 Conta</h3>
          <p>Você é responsável por:</p>
          <ul>
            <li>Manter a confidencialidade de sua senha</li>
            <li>Todas as atividades em sua conta</li>
            <li>Notificar-nos sobre uso não autorizado</li>
          </ul>

          <h2>3. Conteúdo</h2>
          <h3>3.1 Seus Anúncios</h3>
          <p>Ao publicar anúncios, você garante que:</p>
          <ul>
            <li>Tem direito de vender os itens</li>
            <li>As informações são precisas e verdadeiras</li>
            <li>Não viola direitos de terceiros</li>
          </ul>

          <h3>3.2 Conteúdo Proibido</h3>
          <p>É proibido publicar:</p>
          <ul>
            <li>Itens ilegais</li>
            <li>Conteúdo difamatório ou ofensivo</li>
            <li>Material protegido por direitos autorais sem autorização</li>
          </ul>

          <h2>4. Limitação de Responsabilidade</h2>
          <p>A Trabvirso não se responsabiliza por:</p>
          <ul>
            <li>Transações entre usuários</li>
            <li>Conteúdo gerado por usuários</li>
            <li>Perdas ou danos indiretos</li>
          </ul>

          <h2>5. Modificações</h2>
          <p>Reservamo-nos o direito de modificar estes termos a qualquer momento. Alterações significativas serão notificadas.</p>

          <h2>6. Contato</h2>
          <p>Para dúvidas sobre os termos:</p>
          <p>E-mail: termos@trabvirso.com</p>
        </section>
      </div>
    </div>
  );
};

export default TermsOfUsePage;
