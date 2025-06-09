<?php
// Controllers/AuthController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'sometimes|string|in:common,admin'
        ]);

        // Verifica se o e-mail já está em uso
        if (User::where('email', $data['email'])->exists()) {
            return response()->json(['error' => 'Email already in use'], 400);
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'role' => $data['role'] ?? 'common'
        ]);

        if (!$user) {
            return response()->json(['error' => 'Failed to create user'], 500);
        }

        return response()->json(['user' => $user], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);
        
        $user = User::where('email', $data['email'])->first();

        if (!$user || !password_verify($data['password'], $user->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        session()->put('user_id', $user->id);
        session()->put('user_role', $user->role);

        return response()->json([
            'message' => 'Logged in successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role
            ]
        ], 200);
    }

    public function logout(Request $request): JsonResponse
    {
        // Verificar se o usuário está logado
        if (!session('user_id')) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }

        // Limpar sessão
        session()->flush();
        session()->regenerate();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    public function me(Request $request): JsonResponse
    {
        $userId = session('user_id');
        
        if (!$userId) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }

        $user = User::find($userId);
        
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at
            ]
        ], 200);
    }
}
