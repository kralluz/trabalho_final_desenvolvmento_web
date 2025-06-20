<?php
// Controllers/ImageController.php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Image;
use App\Models\User;
use App\Services\CloudinaryService;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{
    protected $cloudinaryService;
    protected $fileUploadService;

    public function __construct(CloudinaryService $cloudinaryService, FileUploadService $fileUploadService)
    {
        $this->cloudinaryService = $cloudinaryService;
        $this->fileUploadService = $fileUploadService;
    }

    private function user()
    {
        return User::find(session('user_id'));
    }

    public function index(): JsonResponse
    {
        $images = Image::with('adsense:id,title')->get();
        return response()->json([
            'success' => true,
            'data' => $images,
            'total' => $images->count(),
            'message' => 'Lista de imagens obtida com sucesso'
        ], 200);
    }

    public function show($id): JsonResponse
    {
        $image = Image::with('adsense:id,title')->find((int)$id);
        if (!$image) {
            return response()->json([
                'success' => false,
                'message' => 'Imagem não encontrada'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $image,
            'message' => 'Imagem obtida com sucesso'
        ], 200);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $file = $request->file('image');
            
            // Use local storage instead of Cloudinary
            $uploadResult = $this->fileUploadService->uploadImage($file);

            // Save image record to database
            $image = new Image([
                'url' => $uploadResult['url'],
                'path' => $uploadResult['path'],
                'metadata' => json_encode($uploadResult['metadata'])
            ]);

            $image->save();

            return response()->json([
                'message' => 'Image uploaded successfully',
                'image' => $image
            ], 201);

        } catch (\Exception $e) {
            Log::error('Image upload error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error uploading image',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $image = Image::find((int)$id);
            if (!$image) {
                return response()->json([
                    'success' => false,
                    'message' => 'Imagem não encontrada'
                ], 404);
            }

            $data = $request->validate([
                'adsense_id' => 'sometimes|integer|exists:adsenses,id'
            ]);

            $image->update($data);
            $image->load('adsense:id,title');

            return response()->json([
                'success' => true,
                'data' => $image,
                'message' => 'Imagem atualizada com sucesso'
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dados de validação inválidos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar imagem',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $image = Image::find((int)$id);
            if (!$image) {
                return response()->json([
                    'success' => false,
                    'message' => 'Imagem não encontrada'
                ], 404);
            }

            // Delete file from storage
            $this->fileUploadService->deleteImage($image->path);
            
            // Delete from Cloudinary if public_id exists
            if ($image->public_id) {
                $this->cloudinaryService->deleteImage($image->public_id);
            }
            
            // Remove the database record
            $image->delete();

            return response()->json([
                'success' => true,
                'message' => 'Imagem deletada com sucesso'
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error deleting image: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao deletar imagem',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
