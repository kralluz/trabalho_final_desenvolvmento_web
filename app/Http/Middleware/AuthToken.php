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
        // Por enquanto, deixar passar tudo sem validação
        // TODO: Implementar validação de token depois
        
        // Simular usuário logado para os testes funcionarem
        $request->merge([
            'auth_user_id' => 1, // Maria Silva
            'auth_user_role' => 'user'
        ]);

        return $next($request);
    }
}
