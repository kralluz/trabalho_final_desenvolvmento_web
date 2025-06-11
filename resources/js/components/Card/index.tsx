import React from "react";
import "./card.style.css";
import imagemTeste from "/resources/js/assets/images/imagemTeste.jpg";

interface CardItens {
  descricao: string,
  preco: string,
  imagem: string,
};

const data: CardItens = {
  descricao: "lorem qualquer bosta aí",
  preco: "R$210.000",
  imagem: "/resources/js/assets/images/imagemTeste.jpg"
};

const Card: React.FC = () => {
  return (
    <>
    <div className="card">
      <img src={imagemTeste} alt="imagem teste do card" className="imagemCard" />
      <h3 className="card-title">Título do Card</h3>
      <p className="card-text">Card </p>
      <p className="card-title">R$210.000,00</p>
    </div>

    <div className="card">
      <img src={imagemTeste} alt="imagem teste do card" className="imagemCard" />
      <h3 className="card-title">Título do Card</h3>
      <p className="card-text">Card </p>
      <p className="card-title">R$210.000,00</p>  
    </div>

    
    </>
  );
};

export default Card;
