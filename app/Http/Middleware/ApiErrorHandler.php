<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Illuminate\Validation\ValidationException;
use Throwable;

class ApiErrorHandler
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            return $next($request);
        } catch (AuthenticationException $e) {
            return response()->json(['error' => 'Unauthorized. Please login first.'], 401);
        } catch (AccessDeniedHttpException $e) {
            return response()->json(['error' => 'Forbidden. Insufficient permissions.'], 403);
        } catch (ModelNotFoundException|NotFoundHttpException $e) {
            return response()->json(['error' => 'Resource not found.'], 404);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Validation failed.', 'messages' => $e->errors()], 422);
        } catch (Throwable $e) {
            // Log do erro detalhado para o sistema
            \Log::error('API Error: ' . $e->getMessage(), [
                'exception' => $e,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Retorna apenas mensagem genérica para o cliente
            return response()->json(['error' => 'Internal server error. Please try again later.'], 500);
        }
    }
} 