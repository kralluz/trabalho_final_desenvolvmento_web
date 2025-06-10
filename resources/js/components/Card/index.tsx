import React from "react";
import "./card.style.css";

const Card: React.FC = () => {
  return (
    <div className="card">
      <img src="\images\imagemTeste.jpg" alt="imagem teste do card" className="imagemCard" />
      <h3 className="card-title">Título do Card</h3>
      <p className="card-text">Card </p>
    </div>
  );
};

export default Card;
