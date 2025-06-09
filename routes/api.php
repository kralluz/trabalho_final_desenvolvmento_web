<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\AdsenseController;

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

// Rotas de saúde e documentação
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'Application is running',
        'timestamp' => now()->toISOString(),
        'version' => app()->version(),
        'environment' => app()->environment(),
    ]);
});

Route::get('/docs', function () {
    return response()->json([
        'titulo' => 'Documentação da API Trabvirso',
        'versao' => '1.0.0',
        'descricao' => 'Endpoints da API da aplicação Trabvirso',
        'url_base' => url('/api'),
        'endpoints' => [
            [
                'metodo' => 'GET',
                'caminho' => '/api/health',
                'descricao' => 'Verifica o status de saúde da aplicação',
                'parametros' => [],
                'exemplo_resposta' => [
                    'status' => 'ok',
                    'message' => 'Application is running',
                    'timestamp' => '2025-06-06T00:46:49.956105Z',
                    'version' => '12.8.0',
                    'environment' => 'local'
                ]
            ],
            [
                'metodo' => 'POST',
                'caminho' => '/api/auth/login',
                'descricao' => 'Realiza login do usuário',
                'parametros' => [
                    'email' => 'string (obrigatório, formato email)',
                    'password' => 'string (obrigatório)'
                ],
                'exemplo_requisicao' => [
                    'email' => 'usuario@exemplo.com',
                    'password' => 'senha123'
                ],
                'exemplo_resposta' => [
                    'sucesso' => true,
                    'dados' => [
                        'usuario' => [
                            'id' => 1,
                            'name' => 'João Silva',
                            'email' => 'joao@exemplo.com',
                            'role' => 'user'
                        ],
                        'token' => 'eyJhbGciOiJIUzI1NiIs...',
                        'tipo_token' => 'Bearer'
                    ],
                    'mensagem' => 'Login realizado com sucesso'
                ]
            ],
            [
                'metodo' => 'GET',
                'caminho' => '/api/adsense',
                'descricao' => 'Lista todos os anúncios',
                'parametros' => [],
                'exemplo_resposta' => [
                    'sucesso' => true,
                    'dados' => [
                        [
                            'id' => 1,
                            'title' => 'Vendo carro',
                            'description' => 'Carro em ótimo estado',
                            'price' => 15000.00,
                            'user_id' => 1,
                            'created_at' => '2025-06-06T00:00:00Z'
                        ]
                    ],
                    'total' => 1,
                    'mensagem' => 'Lista de anúncios obtida com sucesso'
                ]
            ],
            [
                'metodo' => 'POST',
                'caminho' => '/api/adsense',
                'descricao' => 'Cria um novo anúncio',
                'autenticacao' => 'Token Bearer obrigatório',
                'parametros' => [
                    'title' => 'string (obrigatório, máx 255 caracteres)',
                    'description' => 'string (obrigatório)',
                    'price' => 'number (obrigatório)'
                ],
                'exemplo_requisicao' => [
                    'title' => 'Vendo notebook',
                    'description' => 'Notebook Dell em perfeito estado',
                    'price' => 2500.00
                ]
            ]
        ],
        'autenticacao' => [
            'tipo' => 'Bearer Token',
            'descricao' => 'Alguns endpoints requerem autenticação usando tokens Bearer',
            'como_autenticar' => [
                '1. Faça login através do endpoint /api/auth/login',
                '2. Use o token retornado no cabeçalho Authorization',
                '3. Formato: Authorization: Bearer {seu-token}'
            ]
        ],
        'respostas_comuns' => [
            '200' => 'Sucesso',
            '201' => 'Criado com sucesso',
            '401' => 'Não autenticado',
            '403' => 'Acesso negado',
            '404' => 'Não encontrado',
            '422' => 'Erro de validação',
            '500' => 'Erro do servidor'
        ],
        'gerado_em' => now()->toISOString()
    ]);
});

// Rotas de autenticação
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});

// Rotas de usuários (mantendo compatibilidade)
Route::get('/usuarios', function () {
    try {
        $usuarios = DB::table('users')
            ->select('id', 'name as nome', 'email', 'role', 'created_at as criado_em', 'updated_at as atualizado_em')
            ->get();

        return response()->json([
            'sucesso' => true,
            'dados' => $usuarios,
            'total' => $usuarios->count(),
            'mensagem' => 'Lista de usuários obtida com sucesso'
        ]);
    } catch (Exception $e) {
        return response()->json([
            'sucesso' => false,
            'mensagem' => 'Erro ao obter usuários: ' . $e->getMessage()
        ], 500);
    }
});

Route::post('/usuarios', function (Request $request) {
    try {
        // Validação
        $dados = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'senha' => 'required|string|min:6',
            'role' => 'sometimes|string|in:user,admin'
        ]);

        // Criar usuário no banco
        $userId = DB::table('users')->insertGetId([
            'name' => $dados['nome'],
            'email' => $dados['email'],
            'password' => Hash::make($dados['senha']),
            'role' => $dados['role'] ?? 'user',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Buscar o usuário criado
        $usuario = DB::table('users')
            ->select('id', 'name as nome', 'email', 'role', 'created_at as criado_em', 'updated_at as atualizado_em')
            ->where('id', $userId)
            ->first();

        return response()->json([
            'sucesso' => true,
            'dados' => $usuario,
            'mensagem' => 'Usuário criado com sucesso'
        ], 201);
    } catch (ValidationException $e) {
        return response()->json([
            'sucesso' => false,
            'mensagem' => 'Dados inválidos',
            'erros' => $e->errors()
        ], 422);
    } catch (Exception $e) {
        return response()->json([
            'sucesso' => false,
            'mensagem' => 'Erro ao criar usuário: ' . $e->getMessage()
        ], 500);
    }
});

// CRUD de Adsense - usando Controller
Route::prefix('adsense')->group(function () {
    Route::get('/', [AdsenseController::class, 'index']);
    Route::get('/{id}', [AdsenseController::class, 'show']);
    Route::post('/', [AdsenseController::class, 'store']);
    Route::put('/{id}', [AdsenseController::class, 'update']);
    Route::delete('/{id}', [AdsenseController::class, 'destroy']);
});
