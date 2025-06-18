import React, { createContext, useContext, useEffect, ReactNode } from 'react';
import { User } from '../types';
import { useAuth } from '../hooks/useAuth';

interface AuthContextType {
  user: User | null;
  loading: boolean;
  error: string | null;
  clearError: () => void;
  login: (credentials: { email: string; password: string }) => Promise<boolean>;
  register: (credentials: { name: string; email: string; password: string; role?: 'user' | 'admin' }) => Promise<boolean>;
  logout: () => Promise<void>;
  isAuthenticated: () => boolean;
}

const AuthContext = createContext<AuthContextType | undefined>(undefined);

interface AuthProviderProps {
  children: ReactNode;
}

export const AuthProvider: React.FC<AuthProviderProps> = ({ children }) => {
  const authHook = useAuth();

  useEffect(() => {
    // Verificar se o usuário está autenticado ao carregar a aplicação
    if (authHook.user === null && localStorage.getItem('auth_token')) {
      authHook.getCurrentUser();
    }
  }, []);

  return (
    <AuthContext.Provider value={authHook}>
      {children}
    </AuthContext.Provider>
  );
};

export const useAuthContext = (): AuthContextType => {
  const context = useContext(AuthContext);
  if (context === undefined) {
    throw new Error('useAuthContext must be used within an AuthProvider');
  }
  return context;
};