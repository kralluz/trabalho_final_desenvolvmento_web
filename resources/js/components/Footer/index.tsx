import "./footer.style.css";

const Footer: React.FC = () => {
  return (
    <footer className="footer">
      <div className="footer-links">
        <a href="/aboutUs">Sobre</a>
        <a href="/contact">Contato</a>
        <a href="/termsOfUse">Termos de Uso</a>
        <a href="/privacyPolicy">Privacidade</a>
        <a href="/help">Ajuda</a>
      </div>
      <div className="footer-copy">
        © {new Date().getFullYear()} Domus. Todos os direitos reservados.
      </div>
    </footer>
  );
};

export default Footer;
