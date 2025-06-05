<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\AdSenseController;

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
        'url_base' => url('/api'),        'endpoints' => [
            [
                'metodo' => 'GET',
                'caminho' => '/api/health',
                'descricao' => 'Verifica o status de saúde da aplicação',
                'parametros' => [],
                'exemplo_resposta' => [
                    'status' => 'ok',
                    'message' => 'Application is running',
                    'timestamp' => '2025-05-28T17:42:43.729936Z',
                    'version' => '10.48.29',
                    'environment' => 'local'
                ]
            ],
            [
                'metodo' => 'GET',
                'caminho' => '/api/docs',
                'descricao' => 'Obtém a documentação da API',
                'parametros' => [],
                'exemplo_resposta' => [
                    'titulo' => 'Documentação da API Trabvirso',
                    'endpoints' => '...'
                ]
            ],
            [
                'metodo' => 'GET',
                'caminho' => '/api/usuarios',
                'descricao' => 'Lista todos os usuários cadastrados',
                'parametros' => [],
                'exemplo_resposta' => [
                    'sucesso' => true,
                    'dados' => [
                        [
                            'id' => 1,
                            'nome' => 'João Silva',
                            'email' => 'joao@exemplo.com',
                            'criado_em' => '2025-05-28T10:00:00Z'
                        ]
                    ],
                    'total' => 3,
                    'mensagem' => 'Lista de usuários obtida com sucesso'
                ]
            ],
            [
                'metodo' => 'POST',
                'caminho' => '/api/usuarios',
                'descricao' => 'Cria um novo usuário',
                'parametros' => [
                    'nome' => 'string (obrigatório, máx 255 caracteres)',
                    'email' => 'string (obrigatório, formato email)',
                    'senha' => 'string (obrigatório, mínimo 6 caracteres)'
                ],
                'exemplo_requisicao' => [
                    'nome' => 'João Silva',
                    'email' => 'joao@exemplo.com',
                    'senha' => 'senha123'
                ],
                'exemplo_resposta' => [
                    'sucesso' => true,
                    'dados' => [
                        'id' => 123,
                        'nome' => 'João Silva',
                        'email' => 'joao@exemplo.com',
                        'criado_em' => '2025-05-28T15:00:00Z'
                    ],
                    'mensagem' => 'Usuário criado com sucesso'
                ]
            ],
            [
                'metodo' => 'GET',
                'caminho' => '/api/user',
                'descricao' => 'Obtém informações do usuário autenticado',
                'autenticacao' => 'Token Bearer obrigatório (Sanctum)',
                'cabecalhos' => [
                    'Authorization' => 'Bearer {seu-token}'
                ],
                'parametros' => [],
                'exemplo_resposta' => [
                    'id' => 1,
                    'name' => 'João Silva',
                    'email' => 'joao@exemplo.com',
                    'email_verified_at' => '2025-05-28T10:00:00.000000Z',
                    'created_at' => '2025-05-28T10:00:00.000000Z',
                    'updated_at' => '2025-05-28T10:00:00.000000Z'
                ]
            ]
        ],
        'autenticacao' => [
            'tipo' => 'Laravel Sanctum',
            'descricao' => 'Alguns endpoints requerem autenticação usando tokens Bearer',
            'como_autenticar' => [
                '1. Faça login através do endpoint /login',
                '2. Use o token retornado no cabeçalho Authorization',
                '3. Formato: Authorization: Bearer {seu-token}'
            ]
        ],
        'respostas_comuns' => [
            '200' => 'Sucesso',
            '401' => 'Não autenticado',
            '404' => 'Não encontrado',
            '422' => 'Erro de validação',
            '500' => 'Erro do servidor'
        ],
        'gerado_em' => now()->toISOString()
    ]);
});

// Listar usuários do banco de dados
Route::get('/usuarios', function () {
    try {
        $usuarios = DB::table('users')
            ->select('id', 'name as nome', 'email', 'created_at as criado_em', 'updated_at as atualizado_em')
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
            'senha' => 'required|string|min:6'
        ]);

        // Criar usuário no banco
        $userId = DB::table('users')->insertGetId([
            'name' => $dados['nome'],
            'email' => $dados['email'],
            'password' => Hash::make($dados['senha']),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Buscar o usuário criado
        $usuario = DB::table('users')
            ->select('id', 'name as nome', 'email', 'created_at as criado_em', 'updated_at as atualizado_em')
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

// CRUD AdSense usando Controller
Route::prefix('adsense')->group(function () {
    Route::get('/', [AdSenseController::class, 'index']);
    Route::post('/', [AdSenseController::class, 'store']);
    Route::get('/{adsense}', [AdSenseController::class, 'show']);
    Route::put('/{adsense}', [AdSenseController::class, 'update']);
    Route::delete('/{adsense}', [AdSenseController::class, 'destroy']);
    
    // Rotas extras
    Route::patch('/{adsense}/toggle-status', [AdSenseController::class, 'toggleStatus']);
    Route::get('/posicao/{posicao}', [AdSenseController::class, 'porPosicao']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
