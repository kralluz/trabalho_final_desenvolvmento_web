import { CardItem } from "../components/PostList";
import cloudinaryService from "../services/cloudinaryService";

const API_BASE = "/api";

function getAuthToken(): string | null {
  return localStorage.getItem("auth_token");
}

async function request<T>(url: string, options: RequestInit = {}): Promise<T> {
  const token = getAuthToken();
  const headers: Record<string, string> = {
    "Content-Type": "application/json",
    Accept: "application/json",
  };
  if (token) {
    headers["Authorization"] = `Bearer ${token}`;
  }
  const response = await fetch(API_BASE + url, {
    headers,
    ...options,
  });
  const data = await response.json();
  if (!response.ok) {
    throw new Error(data.message || "API Error");
  }
  return data;
}

export async function getMyAdsenses(): Promise<CardItem[]> {
  const res = await request<{ success: boolean; data: any[] }>(
    "/adsense/my/dashboard",
    { method: "GET" }
  );
  return res.data.map(item => ({
    id: item.id,
    titulo: item.title,
    descricao: item.description,
    preco: `R$${item.price}`,
    imagem: item.image_url || null,
  }));
}

/** Fetch all adsenses for Home page */
export async function getHomeAdsenses(): Promise<CardItem[]> {
  const res = await request<{ success: boolean; data: any[] }>(
    "/adsense/home",
    { method: "GET" }
  );
  return res.data.map(item => ({
    id: item.id,
    titulo: item.title,
    descricao: item.description,
    preco: `R$${item.price}`,
    imagem: item.image_url || null,
  }));
}

async function uploadToCloudinary(file: File): Promise<string> {
  const result = await cloudinaryService.uploadImage(file);
  return result.secure_url;
}

export async function createAdsense(data: {
  titulo: string;
  descricao: string;
  preco: string;
  images?: File[];
}): Promise<{ id: number }> {
  let imageUrl = '';
  
  // Upload da imagem para o Cloudinary se houver
  if (data.images && data.images.length > 0) {
    imageUrl = await uploadToCloudinary(data.images[0]);
  }

  const body = {
    title: data.titulo,
    description: data.descricao,
    price: parseFloat(data.preco.replace(/[^0-9\.]/g, "")),
    image_url: imageUrl
  };

  const res = await request<{ success: boolean; data: any }>(
    "/adsense",
    { method: "POST", body: JSON.stringify(body) }
  );
  return { id: res.data.id };
}

export async function updateAdsense(
  id: number,
  data: { 
    titulo: string; 
    descricao: string; 
    preco: string;
    images?: File[];
  }
): Promise<CardItem> {
  let imageUrl = '';
  
  // Upload da imagem para o Cloudinary se houver
  if (data.images && data.images.length > 0) {
    imageUrl = await uploadToCloudinary(data.images[0]);
  }

  const body = {
    title: data.titulo,
    description: data.descricao,
    price: parseFloat(data.preco.replace(/[^0-9\.]/g, "")),
    image_url: imageUrl || undefined
  };

  const res = await request<{ success: boolean; data: any }>(
    `/adsense/${id}`,
    { method: "PUT", body: JSON.stringify(body) }
  );
  
  const item = res.data;
  return {
    id: item.id,
    titulo: item.title,
    descricao: item.description,
    preco: `R$${item.price}`,
    imagem: item.image_url || null,
  };
}

export async function deleteAdsense(id: number): Promise<void> {
  await request<{ success: boolean; message: string }>(
    `/adsense/${id}`,
    { method: "DELETE" }
  );
}
