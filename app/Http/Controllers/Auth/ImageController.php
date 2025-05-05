<?php
// Controllers/ImageController.php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Image;
use App\Models\User;
use App\Services\CloudinaryService;

class ImageController extends Controller
{
    protected $cloudinaryService;

    public function __construct(CloudinaryService $cloudinaryService)
    {
        $this->cloudinaryService = $cloudinaryService;
    }

    private function user()
    {
        return User::find(session('user_id'));
    }

    public function index(): JsonResponse
    {
        return response()->json(['images' => Image::all()], 200);
    }

    public function show($id): JsonResponse
    {
        $image = Image::find((int)$id);
        if (!$image) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return response()->json(['image' => $image], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $user = $this->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $data = $request->validate([
            'adsense_id' => 'required|integer|exists:adsenses,id',
            'image' => 'required|image|max:10240' // Max 10MB
        ]);
        
        try {
            // Upload da imagem para o Cloudinary
            $uploadResult = $this->cloudinaryService->uploadImage($request->file('image'));
            
            // Salvar metadados no banco de dados
            $image = Image::create([
                'adsense_id' => $data['adsense_id'],
                'url' => $uploadResult['url'],
                'public_id' => $uploadResult['public_id'],
                'metadata' => [
                    'format' => $uploadResult['format'],
                    'width' => $uploadResult['width'],
                    'height' => $uploadResult['height'],
                    'bytes' => $uploadResult['bytes']
                ]
            ]);

            if (!$image) {
                // Se falhar a criação no banco, tenta remover do Cloudinary
                $this->cloudinaryService->deleteImage($uploadResult['public_id']);
                return response()->json(['error' => 'Failed to save image metadata'], 500);
            }

            return response()->json(['image' => $image], 201);
        } catch (\Exception $e) {
            \Log::error('Error uploading image: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to upload image: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        $user = $this->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $image = Image::find((int)$id);
        if (!$image) {
            return response()->json(['error' => 'Not found'], 404);
        }

        try {
            // Remover a imagem do Cloudinary se tiver um public_id
            if ($image->public_id) {
                $this->cloudinaryService->deleteImage($image->public_id);
            }
            
            // Remover o registro do banco de dados
            if (!$image->delete()) {
                return response()->json(['error' => 'Failed to delete image record'], 500);
            }

            return response()->json(['message' => 'Image deleted successfully'], 200);
        } catch (\Exception $e) {
            \Log::error('Error deleting image: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete image: ' . $e->getMessage()], 500);
        }
    }
}
