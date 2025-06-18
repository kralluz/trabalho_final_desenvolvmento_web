// Serviço de API para comunicação com o backend
const API_BASE_URL = 'http://localhost:8000/api';

export interface User {
  id: number;
  name: string;
  email: string;
  role: string;
  created_at: string;
}

export interface RegisterRequest {
  name: string;
  email: string;
  password: string;
  role?: string;
}

export interface LoginRequest {
  email: string;
  password: string;
}

export interface ApiResponse<T> {
  success: boolean;
  data?: T;
  message: string;
  errors?: Record<string, string[]>;
}

export interface AuthResponse {
  user: User;
  token: string;
  token_type: string;
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
}

export interface CreateAdsenseRequest {
  title: string;
  description: string;
  price: number;
}

export interface UpdateAdsenseRequest {
  title?: string;
  description?: string;
  price?: number;
}

class ApiService {  private async request<T>(
    endpoint: string,
    options: RequestInit = {}
  ): Promise<ApiResponse<T>> {
    const url = `${API_BASE_URL}${endpoint}`;
    
    // Obter token do localStorage se disponível
    const token = localStorage.getItem('token');
    
    const config: RequestInit = {
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        ...(token && { 'Authorization': `Bearer ${token}` }),
        ...options.headers,
      },
      ...options,
    };

    try {
      const response = await fetch(url, config);
      const data = await response.json();

      if (!response.ok) {
        throw new Error(data.message || `HTTP error! status: ${response.status}`);
      }

      return data;
    } catch (error) {
      console.error('API Error:', error);
      throw error;
    }
  }

  // Autenticação
  async register(userData: RegisterRequest): Promise<ApiResponse<{ user: User }>> {
    return this.request<{ user: User }>('/auth/register', {
      method: 'POST',
      body: JSON.stringify(userData),
    });
  }
  async login(credentials: LoginRequest): Promise<ApiResponse<AuthResponse>> {
    const response = await this.request<AuthResponse>('/auth/login', {
      method: 'POST',
      body: JSON.stringify(credentials),
    });
    
    // Salvar token no localStorage se o login for bem-sucedido
    if (response.success && response.data?.token) {
      localStorage.setItem('token', response.data.token);
      localStorage.setItem('user', JSON.stringify(response.data.user));
    }
    
    return response;
  }
  async logout(): Promise<ApiResponse<null>> {
    const response = await this.request<null>('/auth/logout', {
      method: 'POST',
    });
    
    // Limpar dados do localStorage
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    
    return response;
  }

  async getCurrentUser(): Promise<ApiResponse<{ user: User }>> {
    return this.request<{ user: User }>('/auth/me');
  }

  // Usuários
  async getUsers(): Promise<ApiResponse<User[]>> {
    return this.request<User[]>('/users');
  }
  // Health check
  async healthCheck(): Promise<ApiResponse<any>> {
    return this.request<any>('/health');
  }  // Adsense CRUD
  async getAdsenses(): Promise<ApiResponse<Adsense[]>> {
    return this.request<Adsense[]>('/adsense');
  }

  // Método para página HOME - público, todas as adsenses
  async getHomeAdsenses(): Promise<ApiResponse<Adsense[]>> {
    return this.request<Adsense[]>('/adsense/home');
  }

  // Método para Dashboard - apenas do usuário logado
  async getDashboardAdsenses(): Promise<ApiResponse<Adsense[]>> {
    return this.request<Adsense[]>('/adsense/my/dashboard');
  }

  async getMyAdsenses(): Promise<ApiResponse<Adsense[]>> {
    return this.request<Adsense[]>('/adsense/my');
  }

  async getAdsense(id: number): Promise<ApiResponse<Adsense>> {
    return this.request<Adsense>(`/adsense/${id}`);
  }

  async createAdsense(adsenseData: CreateAdsenseRequest): Promise<ApiResponse<Adsense>> {
    return this.request<Adsense>('/adsense', {
      method: 'POST',
      body: JSON.stringify(adsenseData),
    });
  }

  async updateAdsense(id: number, adsenseData: UpdateAdsenseRequest): Promise<ApiResponse<Adsense>> {
    return this.request<Adsense>(`/adsense/${id}`, {
      method: 'PUT',
      body: JSON.stringify(adsenseData),
    });
  }

  async deleteAdsense(id: number): Promise<ApiResponse<null>> {
    return this.request<null>(`/adsense/${id}`, {
      method: 'DELETE',
    });
  }
  // Método para obter usuário logado do localStorage
  getCurrentUserFromStorage(): User | null {
    const userStr = localStorage.getItem('user');
    return userStr ? JSON.parse(userStr) : null;
  }

  // Método para verificar se está autenticado
  isAuthenticated(): boolean {
    return !!localStorage.getItem('token');
  }
}

export const apiService = new ApiService();
export default apiService;
