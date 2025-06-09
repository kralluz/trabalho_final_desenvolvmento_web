<?php

echo "=== TESTE DAS ROTAS DA API ===\n\n";

// URLs para testar
$urls = [
    'http://localhost:8000',
    'http://localhost:8000/api/health',
    'http://localhost:8000/api/docs',
    'http://localhost:8000/api/usuarios'
];

foreach ($urls as $url) {
    echo "Testando: $url\n";
    echo str_repeat('-', 50) . "\n";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        echo "ERRO cURL: $error\n";
    } else {
        echo "Código HTTP: $httpCode\n";
        
        // Separar header e body
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $headerSize);
        $body = substr($response, $headerSize);
        
        if ($httpCode == 200) {
            echo "SUCESSO!\n";
            echo "Resposta: " . substr($body, 0, 200) . (strlen($body) > 200 ? '...' : '') . "\n";
        } else {
            echo "ERRO HTTP $httpCode\n";
            echo "Resposta: " . substr($body, 0, 200) . "\n";
        }
    }
    
    echo "\n" . str_repeat('=', 50) . "\n\n";
}
