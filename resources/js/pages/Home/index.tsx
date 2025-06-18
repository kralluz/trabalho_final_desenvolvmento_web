import React from 'react';
import { HomeProvider, useHome } from '../../contexts/HomeContext';
import AdsenseCard from '../../components/AdsenseCard';

const HomeContent: React.FC = () => {
  const { adsenseList, loading, error } = useHome();

  if (loading) {
    return (
      <div className="flex justify-center items-center p-8">
        <div className="text-lg">Carregando anúncios...</div>
      </div>
    );
  }

  if (error) {
    return (
      <div className="text-red-500 text-center p-4">
        <p>Erro: {error}</p>
      </div>
    );
  }

  return (
    <div className="container mx-auto px-4 py-8">
      <h1 className="text-3xl font-bold text-center mb-8">
        Todos os Anúncios
      </h1>
      
      {adsenseList.length === 0 ? (
        <div className="text-center p-8">
          <p className="text-gray-500">Nenhum anúncio encontrado.</p>
        </div>
      ) : (
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {adsenseList.map((adsense) => (
            <AdsenseCard
              key={adsense.id}
              adsense={adsense}
              showActions={false} // Não mostrar botões de editar/excluir na página Home
              onEdit={() => {}} // Função vazia
              onDelete={() => {}} // Função vazia
            />
          ))}
        </div>
      )}
      
      <div className="text-center mt-8 text-sm text-gray-500">
        Total de anúncios: {adsenseList.length}
      </div>
    </div>
  );
};

const Home: React.FC = () => {
  return (
    <HomeProvider>
      <HomeContent />
    </HomeProvider>
  );
};

export default Home;
