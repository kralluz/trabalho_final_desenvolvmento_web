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
import { UpdateUserData } from '../hooks/useAuth';

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
      'X-Requested-With': 'XMLHttpRequest',
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
      
      // Handle different response types
      let data;
      const contentType = response.headers.get('content-type');
      if (contentType && contentType.includes('application/json')) {
        data = await response.json();
      } else {
        data = { success: false, message: 'Invalid response format' };
      }

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
    try {
      const response = await this.request<AuthResponse>('/auth/login', {
        method: 'POST',
        body: JSON.stringify(credentials),
      });

      if (response.success && response.data?.token) {
        this.setToken(response.data.token);
      }

      return response;
    } catch (error) {
      console.error('Login error:', error);
      throw error;
    }
  }

  async register(credentials: RegisterCredentials): Promise<ApiResponse<{ user: User }>> {
    try {
      return await this.request('/auth/register', {
        method: 'POST',
        body: JSON.stringify(credentials),
      });
    } catch (error) {
      console.error('Register error:', error);
      throw error;
    }
  }

  async logout(): Promise<ApiResponse> {
    try {
      const response = await this.request<ApiResponse>('/auth/logout', {
        method: 'POST',
      });
      this.clearToken();
      return response;
    } catch (error) {
      console.error('Logout error:', error);
      this.clearToken();
      throw error;
    }
  }

  async getCurrentUser(): Promise<ApiResponse<{ user: User }>> {
    try {
      return await this.request('/auth/me');
    } catch (error) {
      console.error('Get current user error:', error);
      // Clear token if unauthorized
      if (error instanceof Error && error.message.includes('401')) {
        this.clearToken();
      }
      throw error;
    }
  }

  // Adsense Methods - Public
  async getPublicAdsenses(): Promise<AdsenseResponse> {
    try {
      return await this.request('/adsense/home');
    } catch (error) {
      console.error('Get public adsenses error:', error);
      throw error;
    }
  }

  async getAdsenseById(id: number): Promise<ApiResponse<Adsense>> {
    try {
      return await this.request(`/adsense/${id}`);
    } catch (error) {
      console.error('Get adsense by ID error:', error);
      throw error;
    }
  }

  // Adsense Methods - Private (require auth)
  async getMyAdsenses(): Promise<AdsenseResponse> {
    try {
      return await this.request('/adsense/my/dashboard');
    } catch (error) {
      console.error('Get my adsenses error:', error);
      throw error;
    }
  }

  async getDashboardAdsenses(): Promise<AdsenseResponse> {
    try {
      return await this.request('/adsense/my/dashboard');
    } catch (error) {
      console.error('Get dashboard adsenses error:', error);
      throw error;
    }
  }

  async createAdsense(data: CreateAdsenseData): Promise<ApiResponse<Adsense>> {
    try {
      return await this.request('/adsense', {
        method: 'POST',
        body: JSON.stringify(data),
      });
    } catch (error) {
      console.error('Create adsense error:', error);
      throw error;
    }
  }

  async updateAdsense(data: UpdateAdsenseData): Promise<ApiResponse<Adsense>> {
    try {
      const { id, ...updateData } = data;
      return await this.request(`/adsense/${id}`, {
        method: 'PUT',
        body: JSON.stringify(updateData),
      });
    } catch (error) {
      console.error('Update adsense error:', error);
      throw error;
    }
  }

  async deleteAdsense(id: number): Promise<ApiResponse> {
    try {
      return await this.request(`/adsense/${id}`, {
        method: 'DELETE',
      });
    } catch (error) {
      console.error('Delete adsense error:', error);
      throw error;
    }
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
  // Health check
  async healthCheck(): Promise<ApiResponse> {
    try {
      return await this.request('/health');
    } catch (error) {
      console.error('Health check error:', error);
      throw error;
    }
  }

  // User Methods
  async updateUser(data: UpdateUserData): Promise<ApiResponse<{ user: User }>> {
    try {
      return await this.request('/auth/user', {
        method: 'PUT',
        body: JSON.stringify(data)
      });
    } catch (error) {
      console.error('Update user error:', error);
      throw error;
    }
  }
}

export default new ApiService();
