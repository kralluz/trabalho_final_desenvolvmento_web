<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email',
                'password' => 'required|string|min:6',
                'role' => 'sometimes|string|in:user,admin'
            ]);

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => $data['role'] ?? 'user'
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role,
                        'created_at' => $user->created_at
                    ]
                ],
                'message' => 'Usuário criado com sucesso'
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dados de validação inválidos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string'
            ]);
            
            $user = User::where('email', $data['email'])->first();

            if (!$user || !Hash::check($data['password'], $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Credenciais inválidas'
                ], 401);
            }

            // Gerar token único e salvar no banco
            $token = hash('sha256', $user->id . time() . random_bytes(32));
            
            // Salvar token no banco
            $user->update(['api_token' => $token]);

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role
                    ],
                    'token' => $token,
                    'token_type' => 'Bearer'
                ],
                'message' => 'Login realizado com sucesso'
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dados de validação inválidos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            // Verificar token Bearer
            $authHeader = $request->header('Authorization');
            if (!$authHeader || strpos($authHeader, 'Bearer ') !== 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token de autorização necessário'
                ], 401);
            }

            $token = substr($authHeader, 7);
            $tokenData = cache()->get('auth_token_' . $token);
            
            if (!$tokenData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token inválido ou expirado'
                ], 401);
            }

            // Remover token do cache
            cache()->forget('auth_token_' . $token);

            return response()->json([
                'success' => true,
                'message' => 'Logout realizado com sucesso'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function me(Request $request): JsonResponse
    {
        try {
            // Verificar token Bearer
            $authHeader = $request->header('Authorization');
            if (!$authHeader || strpos($authHeader, 'Bearer ') !== 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token de autorização necessário'
                ], 401);
            }

            $token = substr($authHeader, 7);
            $tokenData = cache()->get('auth_token_' . $token);
            
            if (!$tokenData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token inválido ou expirado'
                ], 401);
            }

            $user = User::find($tokenData['user_id']);
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuário não encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role,
                        'created_at' => $user->created_at,
                        'updated_at' => $user->updated_at
                    ]
                ],
                'message' => 'Dados do usuário obtidos com sucesso'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
