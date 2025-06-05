<?php
// Controllers/UserController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(['users' => User::all()], 200);
    }

    public function show($id): JsonResponse
    {
        $user = User::find((int)$id);
        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }

        return response()->json(['user' => $user], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'sometimes|string|in:common,admin'
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'role' => $data['role'] ?? 'common'
        ]);

        if (!$user) {
            return response()->json(['error' => 'Erro ao salvar usuário'], 500);
        }

        return response()->json(['user' => $user], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $user = User::find((int)$id);
        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'sometimes|string|min:6',
            'role' => 'sometimes|string|in:common,admin'
        ]);
        
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->role = $data['role'] ?? $user->role;
        
        if (!empty($data['password'])) {
            $user->password = bcrypt($data['password']);
        }

        if (!$user->save()) {
            return response()->json(['error' => 'Erro ao atualizar usuário'], 500);
        }

        return response()->json(['user' => $user], 200);
    }

    public function destroy($id): JsonResponse
    {
        $user = User::find((int)$id);
        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }

        if (!$user->delete()) {
            return response()->json(['error' => 'Erro ao excluir usuário'], 500);
        }

        return response()->json(['message' => 'Usuário deletado com sucesso'], 200);
    }

    public function adsenses($id): JsonResponse
    {
        $user = User::find((int)$id);
        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }

        return response()->json(['adsenses' => $user->adsenses], 200);
    }
}
