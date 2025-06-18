import { createContext, ReactNode, useState, useContext, useEffect } from "react";
import { apiService, Adsense } from "../services/api";

type HomeContextType = {
  adsenseList: Adsense[];
  loading: boolean;
  error: string | null;
  loadHomeAdsenses: () => Promise<void>;
};

const HomeContext = createContext<HomeContextType | undefined>(undefined);

export const HomeProvider = ({ children }: { children: ReactNode }) => {
  const [adsenseList, setAdsenseList] = useState<Adsense[]>([]);
  const [loading, setLoading] = useState<boolean>(false);
  const [error, setError] = useState<string | null>(null);

  const loadHomeAdsenses = async () => {
    setLoading(true);
    setError(null);
    
    try {
      const response = await apiService.getHomeAdsenses();
      if (response.success && response.data) {
        setAdsenseList(response.data);
      } else {
        setError(response.message || 'Erro ao carregar anúncios');
      }
    } catch (err) {
      setError('Erro ao conectar com o servidor');
      console.error('Erro ao carregar adsenses da home:', err);
    } finally {
      setLoading(false);
    }
  };

  // Carregar adsenses ao montar o componente
  useEffect(() => {
    loadHomeAdsenses();
  }, []);

  const value: HomeContextType = {
    adsenseList,
    loading,
    error,
    loadHomeAdsenses,
  };

  return (
    <HomeContext.Provider value={value}>
      {children}
    </HomeContext.Provider>
  );
};

export const useHome = () => {
  const context = useContext(HomeContext);
  if (context === undefined) {
    throw new Error("useHome precisa estar dentro de HomeProvider");
  }
  return context;
};
