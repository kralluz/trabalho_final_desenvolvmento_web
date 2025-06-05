<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\AdminController;
use App\Http\Controllers\Auth\AdsenseController;
use App\Http\Controllers\Auth\ImageController;
use App\Http\Controllers\Auth\UserController;

// Autenticação
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Área do usuário autenticado
Route::middleware('auth.session')->group(function () {
    
    // Posts (Adsense)
    Route::get('/adsenses', [AdsenseController::class, 'index']);
    Route::get('/adsenses/{id}', [AdsenseController::class, 'show']);
    Route::post('/adsenses', [AdsenseController::class, 'store']);
    Route::put('/adsenses/{id}', [AdsenseController::class, 'update']);
    Route::delete('/adsenses/{id}', [AdsenseController::class, 'destroy']);

    // Imagens
    Route::get('/images', [ImageController::class, 'index']);
    Route::get('/images/{id}', [ImageController::class, 'show']);
    Route::post('/images', [ImageController::class, 'store']);
    Route::delete('/images/{id}', [ImageController::class, 'destroy']);

    // Administração
    Route::middleware('admin')->group(function () {
        Route::get('/admin/stats', [AdminController::class, 'stats']);
        Route::get('/admin/users', [AdminController::class, 'allUsers']);
        Route::get('/admin/posts', [AdminController::class, 'allPosts']);
    });
});
