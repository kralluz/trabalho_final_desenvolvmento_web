import React, { createContext, useContext, ReactNode } from 'react';
import { Adsense } from '../types';
import { useAdsense } from '../hooks/useAdsense';

interface AdsenseContextType {
  adsenses: Adsense[];
  loading: boolean;
  error: string | null;
  clearError: () => void;
  loadPublicAdsenses: () => Promise<void>;
  loadMyAdsenses: () => Promise<void>;
  loadDashboardAdsenses: () => Promise<void>;
  createAdsense: (data: { title: string; description: string; price: number }) => Promise<boolean>;
  updateAdsense: (data: { id: number; title: string; description: string; price: number }) => Promise<boolean>;
  deleteAdsense: (id: number) => Promise<boolean>;
}

const AdsenseContext = createContext<AdsenseContextType | undefined>(undefined);

interface AdsenseProviderProps {
  children: ReactNode;
}

export const AdsenseProvider: React.FC<AdsenseProviderProps> = ({ children }) => {
  const adsenseHook = useAdsense();

  return (
    <AdsenseContext.Provider value={adsenseHook}>
      {children}
    </AdsenseContext.Provider>
  );
};

export const useAdsenseContext = (): AdsenseContextType => {
  const context = useContext(AdsenseContext);
  if (context === undefined) {
    throw new Error('useAdsenseContext must be used within an AdsenseProvider');
  }
  return context;
};
