<?php

/**
 * Script para testar as rotas de autenticação e CRUD de Adsense
 * usando os Controllers implementados
 */

$baseUrl = 'http://localhost:8000/api';

function makeRequest($url, $method = 'GET', $data = null, $headers = []) {
    $ch = curl_init();
    
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_HTTPHEADER => array_merge([
            'Content-Type: application/json',
            'Accept: application/json'
        ], $headers),
        CURLOPT_TIMEOUT => 30
    ]);
    
    if ($data && in_array($method, ['POST', 'PUT', 'PATCH'])) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        return ['error' => $error];
    }
    
    return [
        'status_code' => $httpCode,
        'data' => json_decode($response, true)
    ];
}

function testEndpoint($name, $url, $method = 'GET', $data = null, $headers = []) {
    echo "\n" . str_repeat('=', 80) . "\n";
    echo "TESTANDO: $name\n";
    echo "URL: $url\n";
    echo "Método: $method\n";
    if ($data) {
        echo "Dados: " . json_encode($data, JSON_PRETTY_PRINT) . "\n";
    }
    echo str_repeat('-', 80) . "\n";
    
    $result = makeRequest($url, $method, $data, $headers);
    
    if (isset($result['error'])) {
        echo "❌ ERRO CURL: " . $result['error'] . "\n";
        return false;
    }
    
    echo "Status Code: " . $result['status_code'] . "\n";
    echo "Resposta: " . json_encode($result['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
    
    if ($result['status_code'] >= 200 && $result['status_code'] < 300) {
        echo "✅ SUCESSO\n";
        return $result['data'];
    } else {
        echo "❌ FALHOU\n";
        return false;
    }
}

echo "🚀 INICIANDO TESTES DAS ROTAS DE AUTENTICAÇÃO E ADSENSE\n";
echo "Data/Hora: " . date('Y-m-d H:i:s') . "\n";

// 1. Testar health check
testEndpoint(
    'Health Check',
    "$baseUrl/health"
);

// 2. Testar registro de usuário
$novoUsuario = [
    'name' => 'João Teste',
    'email' => 'joao.teste@example.com',
    'password' => '123456',
    'role' => 'common'
];

$userCreated = testEndpoint(
    'Registro de Usuário',
    "$baseUrl/auth/register",
    'POST',
    $novoUsuario
);

// 3. Testar login
$loginData = [
    'email' => 'joao.teste@example.com',
    'password' => '123456'
];

$loginResponse = testEndpoint(
    'Login do Usuário',
    "$baseUrl/auth/login",
    'POST',
    $loginData
);

// Se o login foi bem-sucedido, usar sessão
$userId = null;
if ($loginResponse && isset($loginResponse['user']['id'])) {
    $userId = $loginResponse['user']['id'];
    
    // Simular token de sessão para próximas requisições
    // Em produção, isso seria um token JWT ou session cookie
    $authHeaders = ['Cookie: laravel_session=fake_session_for_user_' . $userId];
    
    // Configurar sessão programaticamente
    session_start();
    $_SESSION['user_id'] = $userId;
    $_SESSION['user_role'] = $loginResponse['user']['role'];
}

// 4. Testar rota /me
testEndpoint(
    'Obter Dados do Usuário Logado',
    "$baseUrl/auth/me"
);

// 5. Listar anúncios (público)
testEndpoint(
    'Listar Todos os Anúncios',
    "$baseUrl/adsense/"
);

// 6. Criar anúncio (requer autenticação)
if ($userId) {
    $novoAnuncio = [
        'title' => 'Vendo Smartphone Samsung',
        'description' => 'Smartphone Samsung Galaxy em ótimo estado, pouco uso, com caixa e acessórios originais.',
        'price' => 899.99
    ];
    
    $adsenseCreated = testEndpoint(
        'Criar Novo Anúncio',
        "$baseUrl/adsense/",
        'POST',
        $novoAnuncio
    );
    
    // 7. Criar outro anúncio para ter mais dados
    $outroAnuncio = [
        'title' => 'Notebook Dell Inspiron',
        'description' => 'Notebook Dell Inspiron 15 3000, Intel Core i5, 8GB RAM, 256GB SSD.',
        'price' => 2199.50
    ];
    
    testEndpoint(
        'Criar Segundo Anúncio',
        "$baseUrl/adsense/",
        'POST',
        $outroAnuncio
    );
    
    // 8. Listar anúncios novamente (deve mostrar os criados)
    $adsenseList = testEndpoint(
        'Listar Anúncios Após Criação',
        "$baseUrl/adsense/"
    );
    
    // 9. Testar visualizar anúncio específico
    if ($adsenseList && isset($adsenseList['dados']) && count($adsenseList['dados']) > 0) {
        $primeiroAnuncio = $adsenseList['dados'][0];
        $adsenseId = $primeiroAnuncio['id'];
        
        testEndpoint(
            'Visualizar Anúncio Específico',
            "$baseUrl/adsense/$adsenseId"
        );
        
        // 10. Testar edição de anúncio
        $adsenseEditado = [
            'title' => 'Vendo Smartphone Samsung (ATUALIZADO)',
            'description' => 'Smartphone Samsung Galaxy em excelente estado, quase novo, com caixa, carregador e película protetora.',
            'price' => 849.99
        ];
        
        testEndpoint(
            'Editar Anúncio',
            "$baseUrl/adsense/$adsenseId",
            'PUT',
            $adsenseEditado
        );
        
        // 11. Verificar se a edição funcionou
        testEndpoint(
            'Verificar Anúncio Editado',
            "$baseUrl/adsense/$adsenseId"
        );
        
        // 12. Testar exclusão de anúncio (vamos deletar o segundo)
        if (count($adsenseList['dados']) > 1) {
            $segundoAnuncio = $adsenseList['dados'][1];
            $segundoAdsenseId = $segundoAnuncio['id'];
            
            testEndpoint(
                'Deletar Segundo Anúncio',
                "$baseUrl/adsense/$segundoAdsenseId",
                'DELETE'
            );
            
            // 13. Verificar se foi deletado
            testEndpoint(
                'Verificar Anúncio Deletado (deve dar 404)',
                "$baseUrl/adsense/$segundoAdsenseId"
            );
        }
    }
}

// 14. Criar usuário admin para testar permissões
$adminUser = [
    'name' => 'Admin Teste',
    'email' => 'admin@example.com',
    'password' => '123456',
    'role' => 'admin'
];

$adminCreated = testEndpoint(
    'Registro de Usuário Admin',
    "$baseUrl/auth/register",
    'POST',
    $adminUser
);

// 15. Login como admin
$adminLogin = [
    'email' => 'admin@example.com',
    'password' => '123456'
];

$adminLoginResponse = testEndpoint(
    'Login como Admin',
    "$baseUrl/auth/login",
    'POST',
    $adminLogin
);

// 16. Testar logout
testEndpoint(
    'Logout do Usuário',
    "$baseUrl/auth/logout",
    'POST'
);

// 17. Testar /me após logout (deve dar erro)
testEndpoint(
    'Tentar Acessar /me Após Logout (deve falhar)',
    "$baseUrl/auth/me"
);

echo "\n" . str_repeat('=', 80) . "\n";
echo "🏁 TESTES CONCLUÍDOS!\n";
echo "Data/Hora: " . date('Y-m-d H:i:s') . "\n";
echo str_repeat('=', 80) . "\n";

?>
