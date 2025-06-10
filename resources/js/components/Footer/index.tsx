import "./footer.style.css";

const Footer: React.FC = () => {
  return (
    <footer className="footer">
      <div className="footer-links">
        <a href="/sobre">Sobre</a>
        <a href="/contato">Contato</a>
        <a href="/termos">Termos de Uso</a>
        <a href="/privacidade">Privacidade</a>
        <a href="/ajuda">Ajuda</a>
      </div>
      <div className="footer-copy">
        © {new Date().getFullYear()} Domus. Todos os direitos reservados.
      </div>
    </footer>
  );
};

export default Footer;
