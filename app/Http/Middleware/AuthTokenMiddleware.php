<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class AuthTokenMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $authHeader = $request->header('Authorization');
        
        if (!$authHeader || strpos($authHeader, 'Bearer ') !== 0) {
            return response()->json([
                'success' => false,
                'message' => 'Token de autorização necessário'
            ], 401);
        }

        $token = substr($authHeader, 7);
        $tokenData = Cache::get('auth_token_' . $token);
        
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
            ], 401);
        }

        // Adicionar dados do usuário ao request
        $request->merge([
            'auth_user_id' => $user->id,
            'auth_user_role' => $user->role,
            'auth_user' => $user
        ]);

        return $next($request);
    }
}
