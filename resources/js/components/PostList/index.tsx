import React from 'react';
import "./card.style.css";
import imagemTeste from "/resources/js/assets/images/imagemTeste.jpg";

export interface CardItem {
  id: number;
  titulo: string;
  descricao: string;
  preco: string;
  imagem: string | null;
}

interface PostListProps {
  cards: CardItem[];
}

export default function PostList({ cards }: PostListProps) {
  return (
    <div className="post-list-container">
      <div className="cards-container">
        {cards.map(card => (
          <div className="card" key={card.id}>
            <img src={card.imagem || undefined} alt="imagem do card" className="imagemCard" />
            <h3 className="card-title">{card.titulo}</h3>
            <p className="card-text">{card.descricao}</p>
            <p className="card-title">{card.preco}</p>
          </div>
        ))}
      </div>
    </div>
  );
}
