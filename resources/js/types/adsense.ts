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
  status: boolean;
  image?: {
    id: number;
    url: string;
    created_at: string;
    updated_at: string;
  };  created_at: string;
  updated_at: string;
  image_url?: string;
  user?: {
    id: number;
    name: string;
    email: string;
  };
}
