import React from "react";
import "./card.style.css";
import imagemTeste from "/resources/js/assets/images/imagemTeste.jpg";

interface CardItens {
  descricao: string;
  preco: string;
  imagem: string;
}

const cardsMock: CardItens[] = [
  {
    descricao: "Primeiro card de exemplo",
    preco: "R$210.000",
    imagem: imagemTeste,
  },
  {
    descricao: "Segundo card de exemplo",
    preco: "R$150.000",
    imagem: imagemTeste,
  },
  {
    descricao: "Terceiro card de exemplo",
    preco: "R$320.000",
    imagem: imagemTeste,
  },
];

const Card: React.FC = () => {
  return (
    <>
      {cardsMock.map((item, idx) => (
        <div className="card" key={idx}>
          <img src={item.imagem} alt="imagem do card" className="imagemCard" />
          <h3 className="card-title">Título do Card</h3>
          <p className="card-text">{item.descricao}</p>
          <p className="card-title">{item.preco}</p>
        </div>
      ))}
    </>
  );
};

export default Card;