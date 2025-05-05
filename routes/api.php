<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\AdminController;
use App\Http\Controllers\Auth\AdsenseController;
use App\Http\Controllers\Auth\ImageController;
use App\Http\Controllers\Auth\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rotas públicas
// Rota de exemplo para demonstrar API REST
Route::get('/info', function () {
    return response()->json([
        'message' => 'API funcionando corretamente',
        'versao' => '1.0',
        'status' => 'online',
        'timestamp' => now()->toIso8601String()
    ]);
});

// Rota de exemplo para listar recursos
Route::get('/recursos', function () {
    return response()->json([
        'recursos' => [
            ['id' => 1, 'nome' => 'Recurso 1', 'descricao' => 'Descrição do recurso 1'],
            ['id' => 2, 'nome' => 'Recurso 2', 'descricao' => 'Descrição do recurso 2'],
            ['id' => 3, 'nome' => 'Recurso 3', 'descricao' => 'Descrição do recurso 3'],
        ]
    ]);
});

// Rotas de Autenticação
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Área do usuário autenticado
Route::middleware('auth.session')->group(function () {
    
    // Rotas do Usuário
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
        Route::get('/{id}/adsenses', [UserController::class, 'adsenses']);
    });
    
    // Adsenses
    Route::prefix('adsenses')->group(function () {
        Route::get('/', [AdsenseController::class, 'index']);
        Route::get('/{id}', [AdsenseController::class, 'show']);
        Route::post('/', [AdsenseController::class, 'store']);
        Route::put('/{id}', [AdsenseController::class, 'update']);
        Route::delete('/{id}', [AdsenseController::class, 'destroy']);
    });

    // Imagens
    Route::prefix('images')->group(function () {
        Route::get('/', [ImageController::class, 'index']);
        Route::get('/{id}', [ImageController::class, 'show']);
        Route::post('/', [ImageController::class, 'store']);
        Route::delete('/{id}', [ImageController::class, 'destroy']);
    });

    // Administração
    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/stats', [AdminController::class, 'stats']);
        Route::get('/users', [AdminController::class, 'allUsers']);
        Route::get('/adsenses', [AdminController::class, 'allAdsenses']);
    });
});
