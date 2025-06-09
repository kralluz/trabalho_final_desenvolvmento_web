<?php
// Controllers/AdsenseController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Adsense;
use App\Models\User;

class AdsenseController extends Controller
{
    private function user()
    {
        return User::find(session('user_id'));
    }

    public function index(): JsonResponse
    {
        $adsenses = Adsense::with('user:id,name,email')->get();
        return response()->json([
            'sucesso' => true,
            'dados' => $adsenses,
            'total' => $adsenses->count(),
            'mensagem' => 'Lista de anúncios obtida com sucesso'
        ], 200);
    }

    public function show($id): JsonResponse
    {
        $adsense = Adsense::with('user:id,name,email')->find((int)$id);
        if (!$adsense) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Anúncio não encontrado'
            ], 404);
        }
        return response()->json([
            'sucesso' => true,
            'dados' => $adsense,
            'mensagem' => 'Anúncio obtido com sucesso'
        ], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $user = $this->user();
        if (!$user) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Não autenticado'
            ], 401);
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0'
        ]);
        
        $adsense = Adsense::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'price' => (float)$data['price'],
            'user_id' => $user->id
        ]);

        if (!$adsense) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Falha ao salvar anúncio'
            ], 500);
        }

        $adsense->load('user:id,name,email');

        return response()->json([
            'sucesso' => true,
            'dados' => $adsense,
            'mensagem' => 'Anúncio criado com sucesso'
        ], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $user = $this->user();
        if (!$user) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Não autenticado'
            ], 401);
        }

        $adsense = Adsense::find((int)$id);
        if (!$adsense) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Anúncio não encontrado'
            ], 404);
        }

        // Verificar se o usuário é dono do anúncio ou é admin
        if ($adsense->user_id != $user->id && $user->role != 'admin') {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Você não tem permissão para editar este anúncio'
            ], 403);
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0'
        ]);
        
        $adsense->title = $data['title'];
        $adsense->description = $data['description'];
        $adsense->price = (float)$data['price'];

        if (!$adsense->save()) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Falha ao atualizar anúncio'
            ], 500);
        }

        $adsense->load('user:id,name,email');

        return response()->json([
            'sucesso' => true,
            'dados' => $adsense,
            'mensagem' => 'Anúncio atualizado com sucesso'
        ], 200);
    }

    public function destroy($id): JsonResponse
    {
        $user = $this->user();
        if (!$user) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Não autenticado'
            ], 401);
        }

        $adsense = Adsense::find((int)$id);
        if (!$adsense) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Anúncio não encontrado'
            ], 404);
        }

        // Verificar se o usuário é dono do anúncio ou é admin
        if ($adsense->user_id != $user->id && $user->role != 'admin') {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Você não tem permissão para deletar este anúncio'
            ], 403);
        }

        if (!$adsense->delete()) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Falha ao deletar anúncio'
            ], 500);
        }

        return response()->json([
            'sucesso' => true,
            'mensagem' => 'Anúncio deletado com sucesso'
        ], 200);
    }
}
