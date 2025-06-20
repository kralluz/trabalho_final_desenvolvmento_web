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
                {' '}
                {cards.length === 0 ? (
                    <div className="no-ads-message">
                        <div className="no-ads-icon">🏠</div>
                        <h3>Nenhum anúncio para para para...</h3>
                        <p>Sua primeira propriedade está esperando para ser anunciada!</p>
                    </div>
                ) : (
                    cards.map((card) => (
                        <div className="card" key={card.id}>
                            <div className="card-image-container">
                                <img src={card.imagem || undefined} alt="Imóvel" className="imagemCard" />
                            </div>

                            <div className="card-content">
                                <h3 className="card-title">{card.titulo}</h3>

                                <p className="card-text card-descricao">{card.descricao}</p>

                                <div className="card-preco">
                                    <span className="label-preco">Preço</span>
                                    <span className="price-value">{card.preco}</span>
                                </div>
                            </div>

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
                    ))
                )}
            </div>
        </div>
    );
}
