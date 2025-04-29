<?php
// Controllers/AuthController.php

require_once __DIR__ . '/../Response.php';
require_once __DIR__ . '/../Requests/RegisterRequest.php';
require_once __DIR__ . '/../Requests/LoginRequest.php';
require_once __DIR__ . '/../Models/User.php';

class AuthController
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        // Verifica se o e-mail já está em uso
        if (User::findByEmail($data['email'])) {
            return new JsonResponse(['error' => 'Email already in use'], 400);
        }

        $user = new User(
            null,
            $data['name'],
            $data['email'],
            password_hash($data['password'], PASSWORD_BCRYPT),
            $data['role'] ?? 'common'
        );

        if (!$user->save()) {
            return new JsonResponse(['error' => 'Failed to create user'], 500);
        }

        return new JsonResponse(['user' => $user], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = User::findByEmail($data['email']);

        if (!$user || !password_verify($data['password'], $user->password)) {
            return new JsonResponse(['error' => 'Invalid credentials'], 401);
        }

        session_start();
        $_SESSION['user_id']   = $user->id;
        $_SESSION['user_role'] = $user->role;

        return new JsonResponse(['message' => 'Logged in'], 200);
    }
}
