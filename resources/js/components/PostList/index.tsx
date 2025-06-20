import './card.style.css';

export interface CardItem {
    id: number;
    titulo: string;
    descricao: string;
    preco: string;
    imagem: string | null;
    status?: string;
    isNew?: boolean;
}

interface PostListProps {
    cards: CardItem[];
    onEdit?: (card: CardItem) => void;
    onDelete?: (card: CardItem) => void;
    showActions?: boolean;
    showLabels?: boolean;
}

export default function PostList({ cards, onEdit, onDelete, showActions, showLabels }: PostListProps) {
    return (
        <div className="post-list-container">
            <div className="cards-container">
                {cards.map((card) => (
                    <div className="card" key={card.id}>
                        {showLabels && (
                            <div className="card-header">
                                {card.status && <span className="card-label card-label-status">{card.status}</span>}
                                {card.isNew && <span className="card-label card-label-new">Novo</span>}
                            </div>
                        )}
                        <img src={card.imagem || undefined} alt="imagem do card" className="imagemCard" />

                        <h3 className="card-title"> Titulo: {card.titulo} </h3>

                        <p className="card-text card-descricao">
                            <span className="label-desc">Descrição:</span> {card.descricao}
                        </p>
                        <p className="card-preco">
                            <span className="label-preco">Preço:</span> {card.preco}
                        </p>
                        {showActions && (
                            <div className="card-actions">
                                <button className="edit-button" onClick={() => onEdit && onEdit(card)}>
                                    Editar
                                </button>
                                <button className="delete-button" onClick={() => onDelete && onDelete(card)}>
                                    Excluir
                                </button>
                            </div>
                        )}
                    </div>
                ))}
            </div>
        </div>
    );
}
