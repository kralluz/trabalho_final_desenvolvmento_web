<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\User;

class ApiTokenAuth
{
    public function handle(Request $request, Closure $next)
    {
        $authHeader = $request->header('Authorization');
        
        if (!$authHeader || strpos($authHeader, 'Bearer ') !== 0) {
            return response()->json([
                'success' => false,
                'message' => 'Token de autorização necessário'
            ], 401);
        }

        $token = substr($authHeader, 7);
        $user = User::where('api_token', $token)->first();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Token inválido ou expirado'
            ], 401);
        }

        // Definir o usuário autenticado na requisição
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        return $next($request);
    }
}
