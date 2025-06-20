<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class CloudinaryController extends Controller
{
    public function generateSignature(): JsonResponse
    {
        $cloudName = config('cloudinary.cloud_name');
        $apiKey = config('cloudinary.api_key');
        $apiSecret = config('cloudinary.api_secret');
        $folder = config('cloudinary.folder');
        
        $timestamp = time();
        
        // Generate the signature string
        $signatureString = "folder={$folder}&timestamp={$timestamp}" . $apiSecret;
        $signature = hash('sha256', $signatureString);
        
        return response()->json([
            'signature' => $signature,
            'timestamp' => $timestamp,
            'cloudName' => $cloudName,
            'apiKey' => $apiKey,
            'folder' => $folder
        ]);
    }
}
