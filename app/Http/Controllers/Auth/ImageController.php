<?php
// Controllers/ImageController.php
require_once __DIR__ . '/../../Requests/JsonResponse.php';
require_once __DIR__ . '/../../Requests/ImageUploadRequest.php';
require_once __DIR__ . '/../../Models/Image.php';
require_once __DIR__ . '/../../Models/User.php';

class ImageController
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
        return new JsonResponse(['images' => Image::all()], 200);
    }

    public function show($id)
    {
        $image = Image::find((int)$id);
        if (!$image) {
            return new JsonResponse(['error' => 'Not found'], 404);
        }

        return new JsonResponse(['image' => $image], 200);
    }

    public function store($request)
    {
        $user = $this->user();
        if (!$user) {
            return new JsonResponse(['error' => 'Unauthorized'], 401);
        }

        $data = $request->validated();
        $image = new Image($data['adsense_id'], null, $data['url']);

        if (!$image->save()) {
            return new JsonResponse(['error' => 'Failed to save image'], 500);
        }

        return new JsonResponse(['image' => $image], 201);
    }

    public function destroy($id)
    {
        $user = $this->user();
        if (!$user) {
            return new JsonResponse(['error' => 'Unauthorized'], 401);
        }

        $image = Image::find((int)$id);
        if (!$image) {
            return new JsonResponse(['error' => 'Not found'], 404);
        }

        if (!$image->delete()) {
            return new JsonResponse(['error' => 'Failed to delete image'], 500);
        }

        return new JsonResponse(['message' => 'Deleted'], 200);
    }
}
