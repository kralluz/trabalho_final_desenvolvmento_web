<?php
// Teste simples da API
header('Content-Type: application/json');

$routes = [
    '/api/health' => [
        'status' => 'ok',
        'message' => 'Application is running',
        'timestamp' => date('c'),
        'version' => 'test',
        'environment' => 'local',
    ],
    '/api/docs' => [
        'titulo' => 'Documentação da API Trabvirso',
        'versao' => '1.0.0',
        'descricao' => 'Endpoints da API da aplicação Trabvirso',
        'endpoints' => [
            ['método' => 'GET', 'caminho' => '/api/health', 'descrição' => 'Health check'],
            ['método' => 'GET', 'caminho' => '/api/usuarios', 'descrição' => 'Lista usuários'],
            ['método' => 'POST', 'caminho' => '/api/usuarios', 'descrição' => 'Cria usuário'],
        ]
    ],
    '/api/usuarios' => [
        'sucesso' => true,
        'dados' => [
            ['id' => 1, 'nome' => 'João Silva', 'email' => 'joao@exemplo.com'],
            ['id' => 2, 'nome' => 'Maria Santos', 'email' => 'maria@exemplo.com'],
        ],
        'total' => 2,
        'mensagem' => 'Lista de usuários obtida com sucesso'
    ]
];

$path = $_SERVER['REQUEST_URI'] ?? '';
$path = strtok($path, '?'); // Remove query parameters

if (isset($routes[$path])) {
    echo json_encode($routes[$path], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
} else {
    http_response_code(404);
    echo json_encode([
        'erro' => 'Rota não encontrada',
        'path' => $path,
        'rotas_disponíveis' => array_keys($routes)
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
?>
