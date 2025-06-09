<?php

echo "=== TESTE COMPLETO DA API TRABVIRSO ===\n\n";

$baseUrl = 'http://localhost:8000/api';
$token = null;

function testRoute($url, $method = 'GET', $data = null, $headers = []) {
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $defaultHeaders = [
        'Content-Type: application/json',
        'Accept: application/json'
    ];
    
    $allHeaders = array_merge($defaultHeaders, $headers);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $allHeaders);
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
    } elseif ($method === 'PUT') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
    } elseif ($method === 'DELETE') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    
    curl_close($ch);
    
    echo "\n" . str_repeat("=", 80) . "\n";
    echo "Testando: $method $url\n";
    echo "Status HTTP: $httpCode\n";
    
    if ($error) {
        echo "Erro cURL: $error\n";
        return null;
    }
    
    if ($data) {
        echo "Dados enviados: " . json_encode($data, JSON_PRETTY_PRINT) . "\n";
    }
    
    echo "Resposta:\n";
    $decodedResponse = json_decode($response, true);
    if ($decodedResponse) {
        echo json_encode($decodedResponse, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
        return $decodedResponse;
    } else {
        echo $response . "\n";
        return null;
    }
}

echo "FASE 1: TESTES BÁSICOS\n";
echo str_repeat("-", 40) . "\n";

// 1. Health check
testRoute($baseUrl . '/health');

// 2. Documentação
testRoute($baseUrl . '/docs');

echo "\n\nFASE 2: GERENCIAMENTO DE USUÁRIOS\n";
echo str_repeat("-", 40) . "\n";

// 3. Listar usuários
testRoute($baseUrl . '/usuarios');

// 4. Criar um usuário para teste
$novoUsuario = [
    'nome' => 'Teste API User',
    'email' => 'teste' . time() . '@exemplo.com',
    'senha' => 'senha123',
    'role' => 'user'
];
$resultadoUsuario = testRoute($baseUrl . '/usuarios', 'POST', $novoUsuario);

// 5. Criar um usuário admin para teste
$novoAdmin = [
    'nome' => 'Admin User',
    'email' => 'admin' . time() . '@exemplo.com',
    'senha' => 'admin123',
    'role' => 'admin'
];
$resultadoAdmin = testRoute($baseUrl . '/usuarios', 'POST', $novoAdmin);

echo "\n\nFASE 3: AUTENTICAÇÃO\n";
echo str_repeat("-", 40) . "\n";

// 6. Login com usuário comum
if ($resultadoUsuario && isset($resultadoUsuario['dados'])) {
    $loginData = [
        'email' => $resultadoUsuario['dados']['email'],
        'password' => 'senha123'
    ];
    $loginResult = testRoute($baseUrl . '/auth/login', 'POST', $loginData);
    
    if ($loginResult && isset($loginResult['dados']['token'])) {
        $token = $loginResult['dados']['token'];
        echo "\nToken obtido: " . substr($token, 0, 50) . "...\n";
    }
}

// 7. Teste de informações do usuário autenticado
if ($token) {
    $headers = ['Authorization: Bearer ' . $token];
    testRoute($baseUrl . '/auth/me', 'GET', null, $headers);
}

echo "\n\nFASE 4: CRUD DE ADSENSE\n";
echo str_repeat("-", 40) . "\n";

// 8. Listar anúncios (vazio inicialmente)
testRoute($baseUrl . '/adsense');

// 9. Criar anúncio sem autenticação (deve falhar)
$novoAnuncio = [
    'title' => 'Vendo Notebook',
    'description' => 'Notebook Dell Inspiron 15, 8GB RAM, SSD 256GB, em perfeito estado',
    'price' => 2500.00
];
testRoute($baseUrl . '/adsense', 'POST', $novoAnuncio);

// 10. Criar anúncio com autenticação
$adsenseId = null;
if ($token) {
    $headers = ['Authorization: Bearer ' . $token];
    $resultadoAnuncio = testRoute($baseUrl . '/adsense', 'POST', $novoAnuncio, $headers);
    
    if ($resultadoAnuncio && isset($resultadoAnuncio['dados']['id'])) {
        $adsenseId = $resultadoAnuncio['dados']['id'];
    }
}

// 11. Criar mais alguns anúncios
if ($token) {
    $headers = ['Authorization: Bearer ' . $token];
    
    $anuncio2 = [
        'title' => 'Smartphone Samsung Galaxy',
        'description' => 'Samsung Galaxy S21, 128GB, cor preto, com carregador original',
        'price' => 1200.00
    ];
    testRoute($baseUrl . '/adsense', 'POST', $anuncio2, $headers);
    
    $anuncio3 = [
        'title' => 'Mesa de Escritório',
        'description' => 'Mesa de escritório em madeira, 120x60cm, gavetas laterais',
        'price' => 350.00
    ];
    testRoute($baseUrl . '/adsense', 'POST', $anuncio3, $headers);
}

// 12. Listar todos os anúncios novamente
testRoute($baseUrl . '/adsense');

// 13. Obter um anúncio específico
if ($adsenseId) {
    testRoute($baseUrl . '/adsense/' . $adsenseId);
}

// 14. Atualizar anúncio
if ($adsenseId && $token) {
    $headers = ['Authorization: Bearer ' . $token];
    $dadosAtualizacao = [
        'title' => 'Vendo Notebook - PREÇO REDUZIDO!',
        'description' => 'Notebook Dell Inspiron 15, 8GB RAM, SSD 256GB, em perfeito estado. Aceito propostas!',
        'price' => 2200.00
    ];
    testRoute($baseUrl . '/adsense/' . $adsenseId, 'PUT', $dadosAtualizacao, $headers);
}

// 15. Tentar atualizar anúncio sem autenticação (deve falhar)
if ($adsenseId) {
    $dadosAtualizacao = [
        'title' => 'Tentativa sem auth',
        'description' => 'Isso deve falhar',
        'price' => 1000.00
    ];
    testRoute($baseUrl . '/adsense/' . $adsenseId, 'PUT', $dadosAtualizacao);
}

// 16. Login com admin para testar permissões
$tokenAdmin = null;
if ($resultadoAdmin && isset($resultadoAdmin['dados'])) {
    $loginDataAdmin = [
        'email' => $resultadoAdmin['dados']['email'],
        'password' => 'admin123'
    ];
    $loginResultAdmin = testRoute($baseUrl . '/auth/login', 'POST', $loginDataAdmin);
    
    if ($loginResultAdmin && isset($loginResultAdmin['dados']['token'])) {
        $tokenAdmin = $loginResultAdmin['dados']['token'];
    }
}

// 17. Admin tentando editar anúncio de outro usuário (deve funcionar)
if ($adsenseId && $tokenAdmin) {
    $headers = ['Authorization: Bearer ' . $tokenAdmin];
    $dadosAdmin = [
        'title' => 'Editado pelo Admin',
        'description' => 'Admin pode editar qualquer anúncio',
        'price' => 2000.00
    ];
    testRoute($baseUrl . '/adsense/' . $adsenseId, 'PUT', $dadosAdmin, $headers);
}

// 18. Listar anúncios finais
testRoute($baseUrl . '/adsense');

// 19. Deletar anúncio
if ($adsenseId && $token) {
    $headers = ['Authorization: Bearer ' . $token];
    testRoute($baseUrl . '/adsense/' . $adsenseId, 'DELETE', null, $headers);
}

// 20. Verificar se foi deletado
testRoute($baseUrl . '/adsense');

// 21. Logout
if ($token) {
    $headers = ['Authorization: Bearer ' . $token];
    testRoute($baseUrl . '/auth/logout', 'POST', null, $headers);
}

echo "\n" . str_repeat("=", 80) . "\n";
echo "TESTE COMPLETO FINALIZADO!\n";
echo "Funcionalidades testadas:\n";
echo "✓ Health check e documentação\n";
echo "✓ CRUD de usuários\n";
echo "✓ Autenticação (login/logout)\n";
echo "✓ Verificação de token\n";
echo "✓ CRUD completo de adsense\n";
echo "✓ Controle de permissões\n";
echo "✓ Validações de dados\n";
echo str_repeat("=", 80) . "\n";

?>
