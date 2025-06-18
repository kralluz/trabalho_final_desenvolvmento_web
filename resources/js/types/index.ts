// Types principais da aplicação
export interface User {
  id: number;
  name: string;
  email: string;
  role: 'user' | 'admin';
  created_at?: string;
}

export interface Adsense {
  id: number;
  title: string;
  description: string;
  price: number;
  user_id: number;
  user?: User;
  created_at: string;
  updated_at: string;
}

export interface AuthResponse {
  success: boolean;
  data: {
    user: User;
    token: string;
    token_type: string;
  };
  message: string;
}

export interface AdsenseResponse {
  success: boolean;
  data: Adsense[];
  total: number;
  message: string;
}

export interface ApiResponse<T = any> {
  success: boolean;
  data?: T;
  message: string;
  errors?: Record<string, string[]>;
  error?: string;
}

export interface LoginCredentials {
  email: string;
  password: string;
}

export interface RegisterCredentials {
  name: string;
  email: string;
  password: string;
  role?: 'user' | 'admin';
}

export interface CreateAdsenseData {
  title: string;
  description: string;
  price: number;
}

export interface UpdateAdsenseData extends CreateAdsenseData {
  id: number;
}
