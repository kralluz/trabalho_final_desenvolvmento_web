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
            $data = $request->validate([
                'adsense_id' => 'required|integer|exists:adsenses,id',
                'image' => 'required|image|max:10240' // Max 10MB
            ]);
            
            // Upload to Cloudinary
            $file = $request->file('image');
            $uploadResult = $this->cloudinaryService->uploadImage($file, null, [
                'folder' => config('cloudinary.folder') . '/' . $data['adsense_id']
            ]);
            
            // Save metadata to database
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

            $image->load('adsense:id,title');

            return response()->json([
                'success' => true,
                'data' => $image,
                'message' => 'Imagem enviada com sucesso'
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dados de validação inválidos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error uploading image: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao enviar imagem',
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
