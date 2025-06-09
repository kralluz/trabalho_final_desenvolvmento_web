<?php

// Teste direto das rotas da API REST
$baseUrl = 'http://localhost:8000/api';

function testRoute($url, $method = 'GET', $data = null) {
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json'
    ]);
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    
    curl_close($ch);
    
    echo "\n" . str_repeat("=", 60) . "\n";
    echo "Testando: $method $url\n";
    echo "Status HTTP: $httpCode\n";
    
    if ($error) {
        echo "Erro cURL: $error\n";
        return;
    }
    
    if ($data) {
        echo "Dados enviados: " . json_encode($data, JSON_PRETTY_PRINT) . "\n";
    }
    
    echo "Resposta:\n";
    $decodedResponse = json_decode($response, true);
    if ($decodedResponse) {
        echo json_encode($decodedResponse, JSON_PRETTY_PRINT) . "\n";
    } else {
        echo $response . "\n";
    }
}

echo "TESTANDO ROTAS DA API REST\n";
echo "=========================\n";

// 1. Teste de saúde
testRoute($baseUrl . '/health');

// 2. Documentação
testRoute($baseUrl . '/docs');

// 3. Listar usuários
testRoute($baseUrl . '/usuarios');

// 4. Criar usuário
$novoUsuario = [
    'nome' => 'Teste API User',
    'email' => 'teste' . time() . '@exemplo.com',
    'senha' => 'senha123'
];
testRoute($baseUrl . '/usuarios', 'POST', $novoUsuario);

// 5. Listar usuários novamente para ver o novo
testRoute($baseUrl . '/usuarios');

echo "\n" . str_repeat("=", 60) . "\n";
echo "TESTE CONCLUÍDO\n";

?>
