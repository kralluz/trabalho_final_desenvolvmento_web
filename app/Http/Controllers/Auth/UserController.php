<?php
// Controllers/UserController.php

require_once __DIR__ . '/../Requests/JsonResponse.php';
require_once __DIR__ . '/../Requests/UserRequest.php';
require_once __DIR__ . '/../Models/User.php';

class UserController
{
    public function index(): JsonResponse
    {
        return new JsonResponse(['users' => User::all()], 200);
    }

    public function show($id): JsonResponse
    {
        $user = User::find((int)$id);
        if (!$user) {
            return new JsonResponse(['error' => 'Usuário não encontrado'], 404);
        }

        return new JsonResponse(['user' => $user], 200);
    }

    public function store($request): JsonResponse
    {
        $data = $request->validated();

        $user = new User(
            null,
            $data['name'],
            $data['email'],
            password_hash($data['password'], PASSWORD_BCRYPT),
            $data['role'] ?? 'common'
        );

        if (!$user->save()) {
            return new JsonResponse(['error' => 'Erro ao salvar usuário'], 500);
        }

        return new JsonResponse(['user' => $user], 201);
    }

    public function update($request, $id): JsonResponse
    {
        $user = User::find((int)$id);
        if (!$user) {
            return new JsonResponse(['error' => 'Usuário não encontrado'], 404);
        }

        $data = $request->validated();
        $user->name     = $data['name'];
        $user->email    = $data['email'];
        $user->role     = $data['role'] ?? $user->role;
        if (!empty($data['password'])) {
            $user->password = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        if (!$user->save()) {
            return new JsonResponse(['error' => 'Erro ao atualizar usuário'], 500);
        }

        return new JsonResponse(['user' => $user], 200);
    }

    public function destroy($id): JsonResponse
    {
        $user = User::find((int)$id);
        if (!$user) {
            return new JsonResponse(['error' => 'Usuário não encontrado'], 404);
        }

        if (!$user->delete()) {
            return new JsonResponse(['error' => 'Erro ao excluir usuário'], 500);
        }

        return new JsonResponse(['message' => 'Usuário deletado com sucesso'], 200);
    }

    public function adsenses($id): JsonResponse
    {
        $user = User::find((int)$id);
        if (!$user) {
            return new JsonResponse(['error' => 'Usuário não encontrado'], 404);
        }

        return new JsonResponse(['adsenses' => $user->adsenses()], 200);
    }
}
