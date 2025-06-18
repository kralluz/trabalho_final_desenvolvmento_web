<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\AdsenseController;
use App\Http\Controllers\Auth\ImageController;

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

// Rota de saúde da aplicação
Route::get('/health', function () {
    return response()->json([
        'success' => true,
        'data' => [
            'status' => 'ok',
            'message' => 'Application is running',
            'timestamp' => now()->toISOString(),
            'version' => app()->version(),
            'environment' => app()->environment(),
        ],
        'message' => 'Aplicação funcionando corretamente'
    ]);
});

// Documentação da API
Route::get('/docs', function () {
    return response()->json([
        'success' => true,
        'data' => [
            'titulo' => 'Documentação da API Trabvirso',
            'versao' => '1.0.0',
            'descricao' => 'Endpoints da API da aplicação Trabvirso',
            'url_base' => url('/api'),
            'endpoints' => [
                [
                    'metodo' => 'GET',
                    'caminho' => '/api/health',
                    'descricao' => 'Verifica o status de saúde da aplicação'
                ],
                [
                    'metodo' => 'POST',
                    'caminho' => '/api/auth/register',
                    'descricao' => 'Registra um novo usuário',
                    'parametros' => [
                        'name' => 'string (obrigatório)',
                        'email' => 'string (obrigatório, formato email)',
                        'password' => 'string (obrigatório, mín 6 caracteres)',
                        'role' => 'string (opcional: user, admin)'
                    ]
                ],
                [
                    'metodo' => 'POST',
                    'caminho' => '/api/auth/login',
                    'descricao' => 'Realiza login do usuário',
                    'parametros' => [
                        'email' => 'string (obrigatório)',
                        'password' => 'string (obrigatório)'
                    ]
                ],
                [
                    'metodo' => 'POST',
                    'caminho' => '/api/auth/logout',
                    'descricao' => 'Realiza logout do usuário'
                ],
                [
                    'metodo' => 'GET',
                    'caminho' => '/api/auth/me',
                    'descricao' => 'Retorna dados do usuário autenticado'
                ],
                [
                    'metodo' => 'GET',
                    'caminho' => '/api/users',
                    'descricao' => 'Lista todos os usuários'
                ],
                [
                    'metodo' => 'GET',
                    'caminho' => '/api/adsense/home',
                    'descricao' => 'Lista todos os anúncios - Página HOME (pública)'
                ],
                [
                    'metodo' => 'GET',
                    'caminho' => '/api/adsense/my/dashboard',
                    'descricao' => 'Lista anúncios do usuário logado - Dashboard (autenticado)',
                    'requer_auth' => true
                ],
                [
                    'metodo' => 'GET',
                    'caminho' => '/api/adsense/{id}',
                    'descricao' => 'Exibe um anúncio específico (público)'
                ],
                [
                    'metodo' => 'POST',
                    'caminho' => '/api/adsense',
                    'descricao' => 'Cria um novo anúncio (autenticado)',
                    'requer_auth' => true,
                    'parametros' => [
                        'title' => 'string (obrigatório)',
                        'description' => 'string (obrigatório)',
                        'price' => 'numeric (obrigatório, min:0)'
                    ]
                ],
                [
                    'metodo' => 'PUT',
                    'caminho' => '/api/adsense/{id}',
                    'descricao' => 'Atualiza um anúncio (autenticado - apenas dono ou admin)',
                    'requer_auth' => true
                ],
                [
                    'metodo' => 'DELETE',
                    'caminho' => '/api/adsense/{id}',
                    'descricao' => 'Remove um anúncio (autenticado - apenas dono ou admin)',
                    'requer_auth' => true
                ]
            ]
        ],
        'message' => 'Documentação obtida com sucesso',
        'gerado_em' => now()->toISOString()
    ]);
});

// Rota para listar usuários
Route::get('/users', function () {
    try {
        $users = DB::table('users')
            ->select('id', 'name', 'email', 'role', 'created_at')
            ->get();
            
        return response()->json([
            'success' => true,
            'data' => $users,
            'total' => $users->count(),
            'message' => 'Lista de usuários obtida com sucesso'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erro ao buscar usuários',
            'error' => $e->getMessage()
        ], 500);
    }
});

// Rotas de autenticação
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});

// CRUD de Adsense
Route::prefix('adsense')->group(function () {
    // Rotas públicas (sem autenticação)
    Route::get('/home', [AdsenseController::class, 'home']); // Página HOME - todas as adsenses
    Route::get('/', [AdsenseController::class, 'index']); // Compatibilidade - listar anúncios
    
    // Rotas protegidas por autenticação
    Route::middleware('auth.token')->group(function () {
        Route::get('/my/dashboard', [AdsenseController::class, 'dashboard']); // Dashboard - apenas meus anúncios
        Route::get('/my', [AdsenseController::class, 'myAdsenses']); // Compatibilidade - meus anúncios
        Route::post('/', [AdsenseController::class, 'store']);
        Route::put('/{id}', [AdsenseController::class, 'update']);
        Route::delete('/{id}', [AdsenseController::class, 'destroy']);
    });
    
    // Rota com parâmetro deve ficar por último
    Route::get('/{id}', [AdsenseController::class, 'show']); // Ver anúncio específico
});

// CRUD de Images
Route::prefix('images')->group(function () {
    Route::get('/', [ImageController::class, 'index']); // Público - listar imagens
    Route::get('/{id}', [ImageController::class, 'show']); // Público - ver imagem
    
    // Rotas protegidas por autenticação
    Route::middleware('auth.token')->group(function () {
        Route::post('/', [ImageController::class, 'store']);
        Route::put('/{id}', [ImageController::class, 'update']);
        Route::delete('/{id}', [ImageController::class, 'destroy']);
    });
});
