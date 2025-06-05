<?php
// Arquivo de teste simples para as rotas da API

header('Content-Type: application/json');

// Verificar se é uma requisição para as rotas da API
$uri = $_SERVER['REQUEST_URI'];

if ($uri === '/api/health') {
    echo json_encode([
        'status' => 'ok',
        'message' => 'Application is running',
        'timestamp' => date('c'),
        'version' => 'test-1.0',
        'environment' => 'local',
    ]);
    exit;
}

if ($uri === '/api/docs') {
    echo json_encode([
        'titulo' => 'Documentação da API Trabvirso',
        'versao' => '1.0.0',
        'descricao' => 'Endpoints da API da aplicação Trabvirso',
        'url_base' => 'http://localhost:8000/api',
        'endpoints' => [
            [
                'metodo' => 'GET',
                'caminho' => '/api/health',
                'descricao' => 'Verifica o status de saúde da aplicação',
            ],
            [
                'metodo' => 'GET',
                'caminho' => '/api/usuarios',
                'descricao' => 'Lista todos os usuários cadastrados',
            ],
            [
                'metodo' => 'POST',
                'caminho' => '/api/usuarios',
                'descricao' => 'Cria um novo usuário',
            ]
        ]
    ]);
    exit;
}

if ($uri === '/api/usuarios') {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        echo json_encode([
            'sucesso' => true,
            'dados' => [
                [
                    'id' => 1,
                    'nome' => 'João Silva',
                    'email' => 'joao@exemplo.com',
                    'criado_em' => '2025-06-05T10:00:00Z'
                ],
                [
                    'id' => 2,
                    'nome' => 'Maria Santos',
                    'email' => 'maria@exemplo.com',
                    'criado_em' => '2025-06-05T11:00:00Z'
                ]
            ],
            'total' => 2,
            'mensagem' => 'Lista de usuários obtida com sucesso'
        ]);
        exit;
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        
        echo json_encode([
            'sucesso' => true,
            'dados' => [
                'id' => rand(100, 999),
                'nome' => $input['nome'] ?? 'Usuário Teste',
                'email' => $input['email'] ?? 'teste@exemplo.com',
                'criado_em' => date('c')
            ],
            'mensagem' => 'Usuário criado com sucesso'
        ]);
        exit;
    }
}

// Se não encontrar a rota, retornar 404
http_response_code(404);
echo json_encode(['erro' => 'Rota não encontrada']);
