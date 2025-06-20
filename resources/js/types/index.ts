// Interfaces principais da aplicação
export interface User {
  id: number;
  name: string;
  email: string;
  role: 'user' | 'admin';
  created_at: string;
  updated_at: string;
  email_verified_at?: string | null;
  avatar?: string;
}

export interface Adsense {
  id: number;
  title: string;
  description: string;
  price: number;
  user_id: number;
  created_at: string;
  updated_at: string;
  user?: User;
  images?: Image[];
}

export interface Image {
  id: number;
  url: string;
  public_id?: string;
  metadata?: any;
  adsense_id: number;
  created_at: string;
  updated_at: string;
}

// Auth related interfaces
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

export interface AuthResponse {
  success: boolean;
  data?: {
    user: User;
    token: string;
    token_type: string;
  };
  message: string;
  errors?: Record<string, string[]>;
}

// Adsense related interfaces
export interface CreateAdsenseData {
  title: string;
  description: string;
  price: number;
}

export interface UpdateAdsenseData {
  id: number;
  title: string;
  description: string;
  price: number;
}

export interface AdsenseResponse {
  success: boolean;
  data: Adsense[];
  total?: number;
  message: string;
}

export interface CreateAdsenseRequest {
  title: string;
  description: string;
  price: number;
  image_url?: string;
}

export interface CloudinaryUploadResponse {
  secure_url: string;
  public_id: string;
  format?: string;
  width?: number;
  height?: number;
}

// Generic API response interface
export interface ApiResponse<T = any> {
  success: boolean;
  data?: T;
  message: string;
  errors?: Record<string, string[]>;
  total?: number;
}

// Navigation interfaces (from Inertia/Laravel)
export interface Auth {
  user: User;
}

export interface BreadcrumbItem {
  title: string;
  href: string;
}

export interface NavGroup {
  title: string;
  items: NavItem[];
}

export interface NavItem {
  title: string;
  href: string;
  icon?: any;
  isActive?: boolean;
}

export interface SharedData {
  name: string;
  quote: { message: string; author: string };
  auth: Auth;
  ziggy: any;
  sidebarOpen: boolean;
  [key: string]: unknown;
}