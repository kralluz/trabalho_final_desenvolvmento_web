<?php
// Middleware/AdminMiddleware.php

require_once __DIR__ . '/../Response.php';

class AdminMiddleware
{
    /**
     * @param array   $request  (not used here)
     * @param Closure $next     next middleware/controller
     * @return JsonResponse|mixed
     */
    public function handle(array $request, Closure $next)
    {
        session_start();
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            return new JsonResponse(['error' => 'Forbidden'], 403);
        }
        return $next($request);
    }
}
