export interface CreateAdsenseRequest {
  title: string;
  description: string;
  price: number;
  image_url?: string;
}

export interface UpdateAdsenseRequest {
  title?: string;
  description?: string;
  price?: number;
  image_url?: string;
}

export interface Adsense {
  id: number;
  title: string;
  description: string;
  price: number;
  user_id: number;
  image_url?: string;
  created_at: string;
  updated_at: string;
  user?: {
    id: number;
    name: string;
    email: string;
  };
}
