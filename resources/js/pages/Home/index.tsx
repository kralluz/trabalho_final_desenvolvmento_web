import React, { useState, useEffect } from 'react';
import PostList, { CardItem } from '@/components/PostList';
import { getHomeAdsenses } from '@/api/adsense';

const Home: React.FC = () => {
  const [cards, setCards] = useState<CardItem[]>([]);

  useEffect(() => {
    const fetchAdsenses = async () => {
      try {
        const data = await getHomeAdsenses();
        setCards(data);
      } catch (error) {
        console.error('Erro ao carregar anúncios:', error);
      }
    };
    fetchAdsenses();
  }, []);

  return (
    <div>
      <h1>Página Home</h1>
      <PostList cards={cards} />
    </div>
  );
};

export default Home;
