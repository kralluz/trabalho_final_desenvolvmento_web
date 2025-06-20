<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class AuthToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
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
        $user = User::where('api_token', $token)->first();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Token inválido ou expirado'
            ], 401);
        }

        // Adicionar dados do usuário ao request
        $request->merge([
            'auth_user_id' => $user->id,
            'auth_user_role' => $user->role,
            'auth_user' => $user
        ]);

        // Injetar o usuário no request para compatibilidade com $request->user()
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        return $next($request);
    }
}
