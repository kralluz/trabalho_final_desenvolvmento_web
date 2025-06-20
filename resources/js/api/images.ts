const API_BASE = "/api";

function getAuthToken(): string | null {
  return localStorage.getItem("auth_token");
}

export async function uploadImage(adsenseId: number, imageFile: File): Promise<string> {
  const token = getAuthToken();
  const formData = new FormData();
  formData.append("adsense_id", adsenseId.toString());
  formData.append("image", imageFile);

  const headers: Record<string, string> = {};
  if (token) {
    headers["Authorization"] = `Bearer ${token}`;
  }

  const response = await fetch(API_BASE + "/images", {
    method: "POST",
    headers,
    body: formData,
  });
  const data = await response.json();
  if (!response.ok) {
    throw new Error(data.message || "Image upload error");
  }
  return data.data.url; // assuming response.data.url contains the image URL
}
