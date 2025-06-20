<?php
// Controllers/AdsenseController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Adsense;
use App\Models\Image;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class AdsenseController extends Controller
{
    private function user()
    {
        return User::find(session('user_id'));
    }    public function index(): JsonResponse
    {
        $adsenses = Adsense::with(['user:id,name,email','images'])->get();
        return response()->json([
            'success' => true,
            'data' => $adsenses->map(function($adsense) {
                $data = $adsense->toArray();
                $data['image_url'] = $adsense->images->first()?->url;
                unset($data['images']);
                return $data;
            }),
            'total' => $adsenses->count(),
            'message' => 'Lista de anúncios obtida com sucesso'
        ], 200);
    }    // Método específico para a página HOME - pública
    public function home(): JsonResponse
    {
        $adsenses = Adsense::with(['user:id,name,email','images'])
                          ->orderBy('created_at', 'desc')
                          ->get();
        
        return response()->json([
            'success' => true,
            'data' => $adsenses->map(function($adsense) {
                $data = $adsense->toArray();
                $data['image_url'] = $adsense->images->first()?->url;
                unset($data['images']);
                return $data;
            }),
            'total' => $adsenses->count(),
            'message' => 'Lista pública de anúncios obtida com sucesso'
        ], 200);
    }    public function show($id): JsonResponse
    {
        $adsense = Adsense::with(['user:id,name,email','images'])->find((int)$id);
        if (!$adsense) {
            return response()->json([
                'success' => false,
                'message' => 'Anúncio não encontrado'
            ], 404);
        }
        $data = $adsense->toArray();
        $data['image_url'] = $adsense->images->first()?->url;
        unset($data['images']);
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Anúncio obtido com sucesso'
        ], 200);
    }public function store(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'image_url' => 'nullable|string|url'
            ]);

            $userId = $request->auth_user_id;
            
            if (is_null($userId)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuário não autenticado'
                ], 401);
            }

            $adsense = Adsense::create([
                'title' => $data['title'],
                'description' => $data['description'],
                'price' => (float)$data['price'],
                'user_id' => $userId
            ]);            if (!empty($data['image_url'])) {
                $image = new Image([
                    'url' => $data['image_url']
                ]);
                $adsense->images()->save($image);
            }

            $adsense->load('user:id,name,email');
            $responseData = $adsense->toArray();
            $responseData['image_url'] = $adsense->images->first()?->url;
            unset($responseData['images']);

            return response()->json([
                'success' => true,
                'data' => $responseData,
                'message' => 'Anúncio criado com sucesso'
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dados de validação inválidos',
                'errors' => $e->errors()
            ], 422);        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $adsense = Adsense::find((int)$id);
            if (!$adsense) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anúncio não encontrado'
                ], 404);
            }

            $userId = $request->auth_user_id;
            $userRole = $request->auth_user_role;

            // Verificar se o usuário é dono do anúncio ou é admin
            if ($adsense->user_id != $userId && $userRole != 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Você não tem permissão para editar este anúncio'
                ], 403);
            }

            $data = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'image_url' => 'nullable|string|url'
            ]);
            
            $adsense->update([
                'title' => $data['title'],
                'description' => $data['description'],
                'price' => $data['price']
            ]);            if (isset($data['image_url'])) {
                // Remove existing images
                $adsense->images()->delete();
                
                // Add new image
                $image = new Image([
                    'url' => $data['image_url']
                ]);
                $adsense->images()->save($image);
            }

            $adsense->load('user:id,name,email');
            $responseData = $adsense->toArray();
            $responseData['image_url'] = $adsense->images->first()?->url;
            unset($responseData['images']);

            return response()->json([
                'success' => true,
                'data' => $responseData,
                'message' => 'Anúncio atualizado com sucesso'
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validation failed for Adsense update', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Dados de validação inválidos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating Adsense', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request, $id): JsonResponse
    {
        try {
            $adsense = Adsense::find((int)$id);
            if (!$adsense) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anúncio não encontrado'
                ], 404);
            }

            // Usar dados do middleware (temporariamente sem validação)
            $userId = $request->auth_user_id;
            $userRole = $request->auth_user_role;

            // Verificar se o usuário é dono do anúncio ou é admin
            if ($adsense->user_id != $userId && $userRole != 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Você não tem permissão para deletar este anúncio'
                ], 403);
            }

            $adsense->images()->delete();
            $adsense->delete();

            return response()->json([
                'success' => true,
                'message' => 'Anúncio deletado com sucesso'
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error deleting Adsense', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }    public function myAdsenses(Request $request): JsonResponse
    {
        $userId = $request->auth_user_id;
        
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Usuário não autenticado'
            ], 401);
        }
        
        $adsenses = Adsense::with(['user:id,name,email','images'])
                          ->where('user_id', $userId)
                          ->orderBy('created_at', 'desc')
                          ->get();
          return response()->json([
            'success' => true,
            'data' => $adsenses->map(function($adsense) {
                $data = $adsense->toArray();
                $data['image_url'] = $adsense->images->first()?->url;
                unset($data['images']);
                return $data;
            }),
            'total' => $adsenses->count(),
            'message' => 'Lista de meus anúncios obtida com sucesso'
        ], 200);
    }    // Método para dashboard - apenas anúncios do usuário logado
    public function dashboard(Request $request): JsonResponse
    {
        $userId = $request->auth_user_id;
        
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Usuário não autenticado'
            ], 401);
        }
        
        $adsenses = Adsense::with(['user:id,name,email','images'])
                          ->where('user_id', $userId)
                          ->orderBy('created_at', 'desc')
                          ->get();
          return response()->json([
            'success' => true,
            'data' => $adsenses->map(function($adsense) {
                $data = $adsense->toArray();
                $data['image_url'] = $adsense->images->first()?->url;
                unset($data['images']);
                return $data;
            }),
            'total' => $adsenses->count(),
            'message' => 'Dashboard - Lista de meus anúncios obtida com sucesso'
        ], 200);
    }
}
