<?php
// Controllers/AdsenseController.php

require_once __DIR__ . '/../../Requests/JsonResponse.php';
require_once __DIR__ . '/../../Requests/AdsenseRequest.php';
require_once __DIR__ . '/../../Models/Adsense.php';
require_once __DIR__ . '/../../Models/User.php';

class AdsenseController 
{
    private function user()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            return null;
        }
        return User::find((int)$_SESSION['user_id']);
    }

    public function index()
    {
        return new JsonResponse(['adsenses' => Adsense::all()], 200);
    }

    public function show($id)
    {
        $adsense = Adsense::find((int)$id);
        if (!$adsense) {
            return new JsonResponse(['error' => 'Not found'], 404);
        }
        return new JsonResponse(['adsense' => $adsense], 200);
    }

    public function store($request)
    {
        $user = $this->user();
        if (!$user) {
            return new JsonResponse(['error' => 'Unauthorized'], 401);
        }

        $data = $request->validated();
        $adsense = new Adsense(
            null,
            $data['title'],
            $data['description'],
            (float)$data['price'],
            $user->id
        );

        if (!$adsense->save()) {
            return new JsonResponse(['error' => 'Failed to save advertisement'], 500);
        }

        return new JsonResponse(['adsense' => $adsense], 201);
    }

    public function update($request, $id)
    {
        $user = $this->user();
        if (!$user) {
            return new JsonResponse(['error' => 'Unauthorized'], 401);
        }

        $adsense = Adsense::find((int)$id);
        if (!$adsense) {
            return new JsonResponse(['error' => 'Not found'], 404);
        }

        if (!AdsensePolicy::update($user, $adsense)) {
            return new JsonResponse(['error' => 'Forbidden'], 403);
        }

        $data = $request->validated();
        $adsense->title = $data['title'];
        $adsense->description = $data['description'];
        $adsense->price = (float)$data['price'];

        if (!$adsense->save()) {
            return new JsonResponse(['error' => 'Failed to update advertisement'], 500);
        }

        return new JsonResponse(['adsense' => $adsense], 200);
    }

    public function destroy($id)
    {
        $user = $this->user();
        if (!$user) {
            return new JsonResponse(['error' => 'Unauthorized'], 401);
        }

        $adsense = Adsense::find((int)$id);
        if (!$adsense) {
            return new JsonResponse(['error' => 'Not found'], 404);
        }

        if (!AdsensePolicy::delete($user, $adsense)) {
            return new JsonResponse(['error' => 'Forbidden'], 403);
        }

        if (!$adsense->delete()) {
            return new JsonResponse(['error' => 'Failed to delete advertisement'], 500);
        }

        return new JsonResponse(['message' => 'Deleted'], 200);
    }
}
