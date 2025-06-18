import { useState, useCallback } from 'react';
import { Adsense, CreateAdsenseData, UpdateAdsenseData } from '../types';
import apiService from '../services/apiService';

export const useAdsense = () => {
  const [adsenses, setAdsenses] = useState<Adsense[]>([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);

  const clearError = useCallback(() => {
    setError(null);
  }, []);

  const loadPublicAdsenses = useCallback(async () => {
    try {
      setLoading(true);
      setError(null);
      const response = await apiService.getPublicAdsenses();
      if (response.success) {
        setAdsenses(response.data);
      }
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Erro ao carregar anúncios');
    } finally {
      setLoading(false);
    }
  }, []);

  const loadMyAdsenses = useCallback(async () => {
    try {
      setLoading(true);
      setError(null);
      const response = await apiService.getMyAdsenses();
      if (response.success) {
        setAdsenses(response.data);
      }
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Erro ao carregar seus anúncios');
    } finally {
      setLoading(false);
    }
  }, []);

  const loadDashboardAdsenses = useCallback(async () => {
    try {
      setLoading(true);
      setError(null);
      const response = await apiService.getDashboardAdsenses();
      if (response.success) {
        setAdsenses(response.data);
      }
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Erro ao carregar dashboard');
    } finally {
      setLoading(false);
    }
  }, []);

  const createAdsense = useCallback(async (data: CreateAdsenseData): Promise<boolean> => {
    try {
      setLoading(true);
      setError(null);
      const response = await apiService.createAdsense(data);
      if (response.success && response.data) {
        setAdsenses(prev => [response.data!, ...prev]);
        return true;
      }
      return false;
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Erro ao criar anúncio');
      return false;
    } finally {
      setLoading(false);
    }
  }, []);

  const updateAdsense = useCallback(async (data: UpdateAdsenseData): Promise<boolean> => {
    try {
      setLoading(true);
      setError(null);
      const response = await apiService.updateAdsense(data);
      if (response.success && response.data) {
        setAdsenses(prev => 
          prev.map(adsense => 
            adsense.id === data.id ? response.data! : adsense
          )
        );
        return true;
      }
      return false;
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Erro ao atualizar anúncio');
      return false;
    } finally {
      setLoading(false);
    }
  }, []);

  const deleteAdsense = useCallback(async (id: number): Promise<boolean> => {
    try {
      setLoading(true);
      setError(null);
      const response = await apiService.deleteAdsense(id);
      if (response.success) {
        setAdsenses(prev => prev.filter(adsense => adsense.id !== id));
        return true;
      }
      return false;
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Erro ao deletar anúncio');
      return false;
    } finally {
      setLoading(false);
    }
  }, []);

  return {
    adsenses,
    loading,
    error,
    clearError,
    loadPublicAdsenses,
    loadMyAdsenses,
    loadDashboardAdsenses,
    createAdsense,
    updateAdsense,
    deleteAdsense,
  };
};
