import { 
  User, 
  Adsense, 
  AuthResponse, 
  AdsenseResponse, 
  ApiResponse,
  LoginCredentials,
  RegisterCredentials,
  CreateAdsenseData,
  UpdateAdsenseData
} from '../types';

class ApiService {
  private baseURL = 'http://localhost:8000/api';
  private token: string | null = null;

  constructor() {
    this.token = localStorage.getItem('auth_token');
  }

  private async request<T = any>(
    endpoint: string, 
    options: RequestInit = {}
  ): Promise<T> {
    const url = `${this.baseURL}${endpoint}`;
      const headers: Record<string, string> = {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      ...(options.headers as Record<string, string>),
    };

    if (this.token) {
      headers['Authorization'] = `Bearer ${this.token}`;
    }

    const config: RequestInit = {
      ...options,
      headers,
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

  // Auth Methods
  async login(credentials: LoginCredentials): Promise<AuthResponse> {
    const response = await this.request<AuthResponse>('/auth/login', {
      method: 'POST',
      body: JSON.stringify(credentials),
    });

    if (response.success && response.data?.token) {
      this.setToken(response.data.token);
    }

    return response;
  }

  async register(credentials: RegisterCredentials): Promise<ApiResponse<{ user: User }>> {
    return this.request('/auth/register', {
      method: 'POST',
      body: JSON.stringify(credentials),
    });
  }

  async logout(): Promise<ApiResponse> {
    try {
      const response = await this.request<ApiResponse>('/auth/logout', {
        method: 'POST',
      });
      this.clearToken();
      return response;
    } catch (error) {
      this.clearToken();
      throw error;
    }
  }

  async getCurrentUser(): Promise<ApiResponse<{ user: User }>> {
    return this.request('/auth/me');
  }

  // Adsense Methods - Public
  async getPublicAdsenses(): Promise<AdsenseResponse> {
    return this.request('/adsense/home');
  }

  async getAdsenseById(id: number): Promise<ApiResponse<Adsense>> {
    return this.request(`/adsense/${id}`);
  }

  // Adsense Methods - Private (require auth)
  async getMyAdsenses(): Promise<AdsenseResponse> {
    return this.request('/adsense/my');
  }

  async getDashboardAdsenses(): Promise<AdsenseResponse> {
    return this.request('/adsense/dashboard');
  }

  async createAdsense(data: CreateAdsenseData): Promise<ApiResponse<Adsense>> {
    return this.request('/adsense', {
      method: 'POST',
      body: JSON.stringify(data),
    });
  }

  async updateAdsense(data: UpdateAdsenseData): Promise<ApiResponse<Adsense>> {
    const { id, ...updateData } = data;
    return this.request(`/adsense/${id}`, {
      method: 'PUT',
      body: JSON.stringify(updateData),
    });
  }

  async deleteAdsense(id: number): Promise<ApiResponse> {
    return this.request(`/adsense/${id}`, {
      method: 'DELETE',
    });
  }

  // Token Management
  setToken(token: string): void {
    this.token = token;
    localStorage.setItem('auth_token', token);
  }

  clearToken(): void {
    this.token = null;
    localStorage.removeItem('auth_token');
  }

  getToken(): string | null {
    return this.token;
  }

  isAuthenticated(): boolean {
    return !!this.token;
  }
}

export default new ApiService();
