<?php
// Controllers/AdminController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use App\Models\Adsense;

class AdminController extends Controller
{
    public function stats(): JsonResponse
    {
        $users = User::all();
        $ads = Adsense::all();

        return response()->json([
            'total_users' => count($users),
            'total_adsenses' => count($ads),
        ], 200);
    }

    public function allUsers(): JsonResponse
    {
        return response()->json(['users' => User::all()], 200);
    }

    public function allAdsenses(): JsonResponse
    {
        return response()->json(['adsenses' => Adsense::all()], 200);
    }
}
