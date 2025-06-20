import React, { createContext, useContext, ReactNode } from 'react';
import { User } from '../types';
import { useAuth as useAuthHook } from '../hooks/useAuth';

interface AuthContextType {
  user: User | null;
  loading: boolean;
  error: string | null;
  initializing: boolean;
  clearError: () => void;
  login: (credentials: { email: string; password: string }) => Promise<boolean>;
  register: (credentials: { name: string; email: string; password: string; role?: 'user' | 'admin' }) => Promise<boolean>;
  updateUser: (data: { name: string; email: string; currentPassword?: string; newPassword?: string; }) => Promise<boolean>;
  logout: () => Promise<void>;
  getCurrentUser: () => Promise<void>;
  isAuthenticated: () => boolean;
  isAdmin: () => boolean;
}

const AuthContext = createContext<AuthContextType | undefined>(undefined);

interface AuthProviderProps {
  children: ReactNode;
}

export const AuthProvider: React.FC<AuthProviderProps> = ({ children }) => {
  const authHook = useAuthHook();

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

// Export useAuth as an alias for useAuthContext for compatibility
export const useAuth = useAuthContext;