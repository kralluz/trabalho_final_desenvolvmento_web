<?php
// Controllers/AdminController.php

require_once __DIR__ . '/../Response.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/Adsense.php';

class AdminController
{
    public function stats(): JsonResponse
    {
        $users = User::all();
        $ads   = Adsense::all();

        return new JsonResponse([
            'total_users' => count($users),
            'total_posts' => count($ads),
        ], 200);
    }

    public function allUsers(): JsonResponse
    {
        return new JsonResponse(['users' => User::all()], 200);
    }

    public function allPosts(): JsonResponse
    {
        return new JsonResponse(['posts' => Adsense::all()], 200);
    }
}
