import { useState, useCallback } from 'react';
import { User, LoginCredentials, RegisterCredentials } from '../types';
import apiService from '../services/apiService';

export const useAuth = () => {
  const [user, setUser] = useState<User | null>(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);

  const clearError = useCallback(() => {
    setError(null);
  }, []);

  const login = useCallback(async (credentials: LoginCredentials): Promise<boolean> => {
    try {
      setLoading(true);
      setError(null);
      const response = await apiService.login(credentials);
      if (response.success && response.data) {
        setUser(response.data.user);
        return true;
      }
      return false;
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Erro ao fazer login');
      return false;
    } finally {
      setLoading(false);
    }
  }, []);

  const register = useCallback(async (credentials: RegisterCredentials): Promise<boolean> => {
    try {
      setLoading(true);
      setError(null);
      const response = await apiService.register(credentials);
      if (response.success) {
        return true;
      }
      return false;
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Erro ao registrar');
      return false;
    } finally {
      setLoading(false);
    }
  }, []);

  const logout = useCallback(async (): Promise<void> => {
    try {
      setLoading(true);
      await apiService.logout();
    } catch (err) {
      console.error('Erro ao fazer logout:', err);
    } finally {
      setUser(null);
      setLoading(false);
    }
  }, []);

  const getCurrentUser = useCallback(async (): Promise<void> => {
    try {
      setLoading(true);
      setError(null);
      const response = await apiService.getCurrentUser();
      if (response.success && response.data) {
        setUser(response.data.user);
      }
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Erro ao obter usuário atual');
      apiService.clearToken();
    } finally {
      setLoading(false);
    }
  }, []);

  const isAuthenticated = useCallback((): boolean => {
    return apiService.isAuthenticated() && !!user;
  }, [user]);

  return {
    user,
    loading,
    error,
    clearError,
    login,
    register,
    logout,
    getCurrentUser,
    isAuthenticated,
  };
};
