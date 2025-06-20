import { useState, useCallback, useEffect } from 'react';
import { User, LoginCredentials, RegisterCredentials } from '../types';
import apiService from '../services/apiService';

export const useAuth = () => {
  const [user, setUser] = useState<User | null>(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const [initializing, setInitializing] = useState(true);

  const clearError = useCallback(() => {
    setError(null);
  }, []);
  // Initialize auth state on mount
  useEffect(() => {
    const initializeAuth = async () => {
      const token = localStorage.getItem('auth_token');
      if (token) {
        try {
          setLoading(true);
          const response = await apiService.getCurrentUser();
          if (response.success && response.data?.user) {
            setUser(response.data.user);
          } else {
            apiService.clearToken();
          }
        } catch (error) {
          console.error('Failed to initialize auth:', error);
          apiService.clearToken();
        } finally {
          setLoading(false);
        }
      }
      setInitializing(false);
    };

    initializeAuth();
  }, []); // Empty dependency array is correct here
  const login = useCallback(async (credentials: LoginCredentials): Promise<boolean> => {
    try {
      setLoading(true);
      setError(null);

      const response = await apiService.login(credentials);

      if (response.success && response.data?.user) {
        setUser(response.data.user);
        return true;
      } else {
        // Handle validation errors from backend
        if (response.errors) {
          const errorMessages = Object.values(response.errors).flat().join(', ');
          setError(errorMessages);
        } else {
          setError(response.message || 'Erro ao fazer login');
        }
        return false;
      }
    } catch (err) {
      let errorMessage = 'Erro ao fazer login';
      
      if (err instanceof Error) {
        try {
          // Try to parse error response if it's a JSON error
          const errorResponse = JSON.parse(err.message);
          if (errorResponse.errors) {
            errorMessage = Object.values(errorResponse.errors).flat().join(', ');
          } else if (errorResponse.message) {
            errorMessage = errorResponse.message;
          } else {
            errorMessage = err.message;
          }
        } catch {
          // If not JSON, use the original error message
          errorMessage = err.message;
        }
      }
      
      setError(errorMessage);
      console.error('Login error:', err);
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
        // After successful registration, user might need to login
        return true;
      } else {
        // Handle validation errors from backend
        if (response.errors) {
          const errorMessages = Object.values(response.errors).flat().join(', ');
          setError(errorMessages);
        } else {
          setError(response.message || 'Erro ao registrar');
        }
        return false;
      }
    } catch (err) {
      let errorMessage = 'Erro ao registrar';
      
      if (err instanceof Error) {
        try {
          // Try to parse error response if it's a JSON error
          const errorResponse = JSON.parse(err.message);
          if (errorResponse.errors) {
            errorMessage = Object.values(errorResponse.errors).flat().join(', ');
          } else if (errorResponse.message) {
            errorMessage = errorResponse.message;
          } else {
            errorMessage = err.message;
          }
        } catch {
          // If not JSON, use the original error message
          errorMessage = err.message;
        }
      }
      
      setError(errorMessage);
      console.error('Register error:', err);
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

      if (response.success && response.data?.user) {
        setUser(response.data.user);
      } else {
        setError(response.message || 'Erro ao obter usuário atual');
        apiService.clearToken();
        setUser(null);
      }
    } catch (err) {
      const errorMessage = err instanceof Error ? err.message : 'Erro ao obter usuário atual';
      setError(errorMessage);
      console.error('Get current user error:', err);
      apiService.clearToken();
      setUser(null);
    } finally {
      setLoading(false);
    }
  }, []);

  const isAuthenticated = useCallback((): boolean => {
    return apiService.isAuthenticated() && !!user;
  }, [user]);

  const isAdmin = useCallback((): boolean => {
    return user?.role === 'admin';
  }, [user]);

  return {
    user,
    loading,
    error,
    initializing,
    clearError,
    login,
    register,
    logout,
    getCurrentUser,
    isAuthenticated,
    isAdmin,
  };
};
