export interface CloudinaryUploadResponse {
    secure_url: string;
    public_id: string;
    format?: string;
    width?: number;
    height?: number;
}

class CloudinaryService {
    private cloudName = 'dg5v35wod';
    private uploadPreset = 'trabvirso_upload'; // Nome do upload preset não assinado
    private getUploadUrl(): string {
        return `https://api.cloudinary.com/v1_1/${this.cloudName}/image/upload`;
    }
    async uploadImage(file: File): Promise<CloudinaryUploadResponse> {
        try {
            const formData = new FormData();
            formData.append('file', file);
            formData.append('upload_preset', this.uploadPreset);
            formData.append('api_key', '188836169722359'); // adicione essa linha

            const response = await fetch(this.getUploadUrl(), {
            method: 'POST',
            body: formData,
            });

            console.log('Uploading to Cloudinary:', {
                url: this.getUploadUrl(),
                preset: this.uploadPreset,
                fileName: file.name,
                fileSize: file.size,
                fileType: file.type,
            });

            if (!response.ok) {
                const errorData = await response.json();
                console.error('Cloudinary error response:', errorData);
                throw new Error(errorData.error?.message || 'Erro no upload da imagem');
            }

            const data = await response.json();
            console.log('Cloudinary success response:', data);
            return {
                secure_url: data.secure_url,
                public_id: data.public_id,
                format: data.format,
                width: data.width,
                height: data.height,
            };
        } catch (error) {
            console.error('Erro no upload para Cloudinary:', error);
            throw error;
        }
    }
}

const cloudinaryService = new CloudinaryService();
export default cloudinaryService;
