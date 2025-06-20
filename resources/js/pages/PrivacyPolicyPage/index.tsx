import React from 'react';
import '../InfoPages.style.css';

const PrivacyPolicyPage: React.FC = () => {
  return (
    <div className="info-container">
      <div className="info-content">
        <h1>Política de Privacidade</h1>

        <section className="info-section">
          <p>Última atualização: 20 de junho de 2025</p>

          <h2>1. Introdução</h2>
          <p>A Trabvirso está comprometida em proteger sua privacidade. Esta Política de Privacidade explica como coletamos, usamos, divulgamos e protegemos suas informações pessoais.</p>

          <h2>2. Informações que Coletamos</h2>
          <ul>
            <li>Informações de cadastro (nome, e-mail, telefone)</li>
            <li>Informações de perfil</li>
            <li>Dados de anúncios</li>
            <li>Informações de uso da plataforma</li>
          </ul>

          <h2>3. Como Usamos suas Informações</h2>
          <ul>
            <li>Fornecer e melhorar nossos serviços</li>
            <li>Personalizar sua experiência</li>
            <li>Processar transações</li>
            <li>Enviar comunicações importantes</li>
            <li>Garantir a segurança da plataforma</li>
          </ul>

          <h2>4. Compartilhamento de Informações</h2>
          <p>Não vendemos suas informações pessoais. Compartilhamos apenas quando:</p>
          <ul>
            <li>Você autoriza expressamente</li>
            <li>É necessário para fornecer o serviço</li>
            <li>Exigido por lei</li>
          </ul>

          <h2>5. Segurança</h2>
          <p>Implementamos medidas de segurança técnicas e organizacionais para proteger suas informações contra acesso não autorizado, perda ou alteração.</p>

          <h2>6. Seus Direitos</h2>
          <p>Você tem direito a:</p>
          <ul>
            <li>Acessar suas informações pessoais</li>
            <li>Corrigir dados incorretos</li>
            <li>Solicitar exclusão de dados</li>
            <li>Revogar consentimento</li>
          </ul>

          <h2>7. Contato</h2>
          <p>Para questões sobre privacidade, entre em contato:</p>
          <p>E-mail: privacidade@trabvirso.com</p>
        </section>
      </div>
    </div>
  );
};

export default PrivacyPolicyPage;
