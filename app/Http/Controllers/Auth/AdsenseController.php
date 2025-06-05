<?php
// Controllers/AdsenseController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Adsense;
use App\Models\User;

class AdsenseController extends Controller
{
    private function user()
    {
        return User::find(session('user_id'));
    }

    public function index(): JsonResponse
    {
        return response()->json(['adsenses' => Adsense::all()], 200);
    }

    public function show($id): JsonResponse
    {
        $adsense = Adsense::find((int)$id);
        if (!$adsense) {
            return response()->json(['error' => 'Not found'], 404);
        }
        return response()->json(['adsense' => $adsense], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $user = $this->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric'
        ]);
        
        $adsense = Adsense::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'price' => (float)$data['price'],
            'user_id' => $user->id
        ]);

        if (!$adsense) {
            return response()->json(['error' => 'Failed to save advertisement'], 500);
        }

        return response()->json(['adsense' => $adsense], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $user = $this->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $adsense = Adsense::find((int)$id);
        if (!$adsense) {
            return response()->json(['error' => 'Not found'], 404);
        }

        // Verificar se o usuário é dono do anúncio ou é admin
        if ($adsense->user_id != $user->id && $user->role != 'admin') {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric'
        ]);
        
        $adsense->title = $data['title'];
        $adsense->description = $data['description'];
        $adsense->price = (float)$data['price'];

        if (!$adsense->save()) {
            return response()->json(['error' => 'Failed to update advertisement'], 500);
        }

        return response()->json(['adsense' => $adsense], 200);
    }

    public function destroy($id): JsonResponse
    {
        $user = $this->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $adsense = Adsense::find((int)$id);
        if (!$adsense) {
            return response()->json(['error' => 'Not found'], 404);
        }

        // Verificar se o usuário é dono do anúncio ou é admin
        if ($adsense->user_id != $user->id && $user->role != 'admin') {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        if (!$adsense->delete()) {
            return response()->json(['error' => 'Failed to delete advertisement'], 500);
        }

        return response()->json(['message' => 'Deleted'], 200);
    }
}
