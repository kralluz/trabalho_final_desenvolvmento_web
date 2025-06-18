import React from 'react';
import { Adsense } from '../../types';
import { formatPrice, formatDateRelative } from '../../utils/formatters';

interface AdsenseCardProps {
  adsense: Adsense;
  showActions?: boolean;
  onEdit?: (adsense: Adsense) => void;
  onDelete?: (id: number) => void;
  className?: string;
}

const AdsenseCard: React.FC<AdsenseCardProps> = ({
  adsense,
  showActions = false,
  onEdit,
  onDelete,
  className = ''
}) => {
  const handleDelete = () => {
    if (window.confirm(`Tem certeza que deseja deletar o anúncio "${adsense.title}"?`)) {
      onDelete?.(adsense.id);
    }
  };

  const handleEdit = () => {
    onEdit?.(adsense);
  };
    if (onEdit) {
      onEdit(adsense);
    }
  };
  return (
    <div className={`bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow ${className}`}>
      <div className="flex justify-between items-start mb-4">
        <h3 className="text-xl font-semibold text-gray-800 truncate">
          {adsense.title}
        </h3>
        <span className="text-2xl font-bold text-green-600">
          {formatPrice(adsense.price)}
        </span>
      </div>
      
      <p className="text-gray-600 mb-4 line-clamp-3">
        {adsense.description}
      </p>
      
      <div className="flex justify-between items-center text-sm text-gray-500 mb-4">
        {adsense.user && (
          <span className="flex items-center">
            <svg className="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
              <path fillRule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clipRule="evenodd" />
            </svg>
            {adsense.user.name}
          </span>
        )}
        <span className="flex items-center">
          <svg className="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
            <path fillRule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clipRule="evenodd" />
          </svg>
          {formatDateRelative(adsense.created_at)}
        </span>
      </div>
      
      {showActions && (
        <div className="flex gap-2 pt-4 border-t border-gray-100">
          <button
            onClick={handleEdit}
            className="flex-1 bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition-colors flex items-center justify-center"
          >
            <svg className="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
              <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
            </svg>
            Editar
          </button>
          <button
            onClick={handleDelete}
            className="flex-1 bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition-colors flex items-center justify-center"
          >
            <svg className="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
              <path fillRule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9zM4 5a2 2 0 012-2h8a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5zM8 8a1 1 0 012 0v3a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v3a1 1 0 102 0V8a1 1 0 00-1-1z" clipRule="evenodd" />
            </svg>
            Excluir
          </button>
        </div>
      )}
    </div>
  );
};

export default AdsenseCard;