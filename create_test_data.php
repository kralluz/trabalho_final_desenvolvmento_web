<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Adsense;

echo "=== CRIANDO USUÁRIOS E ANÚNCIOS DE TESTE ===\n\n";

// Criando 3 usuários
$users = [
    [
        'name' => 'João Silva',
        'email' => 'joao.silva@email.com',
        'password' => '123456',
        'role' => 'user'
    ],
    [
        'name' => 'Maria Santos',
        'email' => 'maria.santos@email.com',
        'password' => '123456',
        'role' => 'user'
    ],
    [
        'name' => 'Pedro Oliveira',
        'email' => 'pedro.oliveira@email.com',
        'password' => '123456',
        'role' => 'user'
    ]
];

$createdUsers = [];

foreach ($users as $userData) {
    $user = User::create($userData);
    $createdUsers[] = $user;
    echo "✅ Usuário criado: {$user->name} ({$user->email}) - ID: {$user->id}\n";
}

echo "\n=== CRIANDO ANÚNCIOS ===\n\n";

// Anúncios realistas para imóveis
$adsensesData = [
    // Anúncios do João Silva
    [
        'title' => 'Casa 3 Quartos - Bairro Jardim das Flores',
        'description' => 'Linda casa com 3 quartos, 2 banheiros, sala ampla, cozinha planejada e área de serviço. Localizada em bairro residencial tranquilo, próxima a escolas e supermercados. Garagem para 2 carros, quintal com área gourmet. Ideal para famílias que buscam conforto e praticidade.',
        'price' => 450000.00,
        'user_id' => $createdUsers[0]->id
    ],
    [
        'title' => 'Apartamento 2 Quartos - Centro da Cidade',
        'description' => 'Apartamento moderno no coração da cidade, com 2 quartos, banheiro social, sala integrada com cozinha americana. Prédio com elevador, portaria 24h e área de lazer completa. Próximo ao comércio, bancos e transporte público. Perfeito para quem busca praticidade urbana.',
        'price' => 320000.00,
        'user_id' => $createdUsers[0]->id
    ],
    
    // Anúncios da Maria Santos
    [
        'title' => 'Sobrado 4 Quartos - Condomínio Fechado',
        'description' => 'Sobrado em condomínio fechado com total segurança e lazer. 4 quartos sendo 2 suítes, 3 banheiros, sala de estar e jantar, cozinha gourmet com ilha. Área privativa com churrasqueira e piscina. Condomínio com quadra, playground e salão de festas.',
        'price' => 780000.00,
        'user_id' => $createdUsers[1]->id
    ],
    [
        'title' => 'Studio Mobiliado - Região Universitária',
        'description' => 'Studio completamente mobiliado e equipado, ideal para estudantes ou profissionais. Ambiente integrado otimizado, banheiro moderno, cozinha compacta com todos os eletrodomésticos. Localização privilegiada próxima às principais universidades e centro médico.',
        'price' => 180000.00,
        'user_id' => $createdUsers[1]->id
    ],
    
    // Anúncios do Pedro Oliveira
    [
        'title' => 'Casa de Campo - Vista para Montanha',
        'description' => 'Casa de campo dos sonhos com vista deslumbrante para as montanhas. 3 quartos, 2 banheiros, sala com lareira, cozinha rústica e varanda gourmet. Terreno de 2.000m² com jardim, pomar e espaço para horta. Refúgio perfeito para quem busca tranquilidade e contato com a natureza.',
        'price' => 650000.00,
        'user_id' => $createdUsers[2]->id
    ],
    [
        'title' => 'Loft Industrial - Bairro Boêmio',
        'description' => 'Loft no estilo industrial em prédio histórico revitalizado. Pé direito alto, estruturas aparentes, amplas janelas com muita luz natural. Ambiente integrado com mezanino, banheiro moderno e cozinha conceito aberto. Localizado no coração do bairro boêmio da cidade.',
        'price' => 420000.00,
        'user_id' => $createdUsers[2]->id
    ]
];

$createdAdsenses = [];

foreach ($adsensesData as $index => $adsenseData) {
    $adsense = Adsense::create($adsenseData);
    $createdAdsenses[] = $adsense;
    $userName = $createdUsers[floor($index / 2)]->name;
    echo "✅ Anúncio criado: '{$adsense->title}' - R$ " . number_format($adsense->price, 2, ',', '.') . " (Usuário: {$userName})\n";
}

echo "\n=== RESUMO ===\n";
echo "👥 Usuários criados: " . count($createdUsers) . "\n";
echo "🏠 Anúncios criados: " . count($createdAdsenses) . "\n";
echo "✨ Dados de teste criados com sucesso!\n\n";

echo "=== DETALHES DOS USUÁRIOS ===\n";
foreach ($createdUsers as $user) {
    $adsenseCount = Adsense::where('user_id', $user->id)->count();
    echo "📋 {$user->name} ({$user->email}) - {$adsenseCount} anúncios\n";
}

echo "\n🎉 Processo concluído!\n";
