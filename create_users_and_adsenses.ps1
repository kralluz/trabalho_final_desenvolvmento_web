# Script para criar usuários e anúncios de teste
Write-Host "Criando usuários e anúncios de teste..." -ForegroundColor Green

# URLs das imagens
$images = @(
    "https://i.pinimg.com/736x/09/6d/bb/096dbbb9d77149ddbeef063873bda8e3.jpg",
    "https://s2-casavogue.glbimg.com/_gViGq_rKxjtetkt3VVlRxpdqN0%3D/0x0%3A620x888/984x0/smart/filters%3Astrip_icc%28%29/i.s3.glbimg.com/v1/AUTH_d72fd4bf0af74c0c89d27a5a226dbbf8/internal_photos/bs/2023/B/B/oU1V25SmKhvTxtMmqYRQ/2017-10-24-mg-0793-1.jpeg",
    "https://i.pinimg.com/1200x/38/0f/ff/380fffedb873d9ad7a625dfc52868745.jpg",
    "https://hips.hearstapps.com/hmg-prod/images/edc020124lauder-004-656776cf4986f.jpg?crop=1xw%3A1xh%3Bcenter%2Ctop&resize=980%3A%2A",
    "http://images.adsttc.com/media/images/5efe/1f7f/b357/6540/5400/01d7/newsletter/archdaily-houses-104.jpg?1593712501="
)

# Criando usuários
$users = @()

Write-Host "1. Criando usuário: João Silva" -ForegroundColor Yellow
$user1 = @{
    name = "João Silva"
    email = "joao@example.com"
    password = "123456"
    role = "user"
} | ConvertTo-Json

try {
    $response1 = Invoke-RestMethod -Uri "http://localhost:8000/api/auth/register" -Method POST -ContentType "application/json" -Body $user1
    Write-Host "✓ Usuário João Silva criado com sucesso!" -ForegroundColor Green
    $users += $response1.data.user
} catch {
    Write-Host "✗ Erro ao criar João Silva: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host "2. Criando usuário: Maria Santos" -ForegroundColor Yellow
$user2 = @{
    name = "Maria Santos"
    email = "maria@example.com"
    password = "123456"
    role = "user"
} | ConvertTo-Json

try {
    $response2 = Invoke-RestMethod -Uri "http://localhost:8000/api/auth/register" -Method POST -ContentType "application/json" -Body $user2
    Write-Host "✓ Usuário Maria Santos criado com sucesso!" -ForegroundColor Green
    $users += $response2.data.user
} catch {
    Write-Host "✗ Erro ao criar Maria Santos: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host "3. Criando usuário: Pedro Oliveira" -ForegroundColor Yellow
$user3 = @{
    name = "Pedro Oliveira"
    email = "pedro@example.com"
    password = "123456"
    role = "user"
} | ConvertTo-Json

try {
    $response3 = Invoke-RestMethod -Uri "http://localhost:8000/api/auth/register" -Method POST -ContentType "application/json" -Body $user3
    Write-Host "✓ Usuário Pedro Oliveira criado com sucesso!" -ForegroundColor Green
    $users += $response3.data.user
} catch {
    Write-Host "✗ Erro ao criar Pedro Oliveira: $($_.Exception.Message)" -ForegroundColor Red
}

# Agora vamos criar anúncios para cada usuário
$adsensesData = @(
    # Anúncios do João Silva
    @{
        user_email = "joao@example.com"
        adsenses = @(
            @{
                title = "Casa Moderna com Piscina - Zona Sul"
                description = "Lindíssima casa térrea com 3 quartos, sendo 1 suíte master, sala ampla com pé direito alto, cozinha gourmet integrada, área de lazer completa com piscina aquecida, churrasqueira e jardim paisagístico. Localizada em condomínio fechado de alto padrão na zona sul. Aceita financiamento."
                price = 850000.00
                image_url = $images[0]
            },
            @{
                title = "Apartamento Cobertura Duplex - Vista Mar"
                description = "Espetacular cobertura duplex com 4 suítes, living integrado de 80m², terraço gourmet com vista panorâmica para o mar, 3 vagas de garagem cobertas, depósito privativo. Prédio com portaria 24h, piscina, academia e salão de festas. Pronto para morar!"
                price = 1200000.00
                image_url = $images[1]
            }
        )
    },
    # Anúncios da Maria Santos
    @{
        user_email = "maria@example.com"
        adsenses = @(
            @{
                title = "Sobrado Familiar - 4 Quartos + Escritório"
                description = "Excelente sobrado em bairro residencial nobre, com 4 quartos amplos, escritório, 3 banheiros, sala de estar e jantar, cozinha planejada, área de serviço completa, quintal com espaço gourmet. Garagem para 2 carros. Ótima localização próxima a escolas e shopping."
                price = 680000.00
                image_url = $images[2]
            },
            @{
                title = "Casa de Condomínio - Segurança 24h"
                description = "Belíssima casa em condomínio fechado de luxo com 3 suítes, sala de estar com lareira, varanda gourmet, piscina privativa, jardim com gramado sintético. O condomínio oferece área de lazer completa, quadras esportivas e segurança 24h. Imperdível para famílias!"
                price = 920000.00
                image_url = $images[3]
            }
        )
    },
    # Anúncios do Pedro Oliveira
    @{
        user_email = "pedro@example.com"
        adsenses = @(
            @{
                title = "Casa Térrea Moderna - Design Arquitetônico"
                description = "Arquitetura contemporânea única! Casa térrea com 3 suítes, sala integrada de 60m² com pé direito duplo, cozinha gourmet com ilha central, área gourmet coberta, piscina com borda infinita. Acabamento de primeira linha com materiais importados. Projeto exclusivo!"
                price = 1050000.00
                image_url = $images[4]
            },
            @{
                title = "Penthouse Exclusiva - Alto Padrão"
                description = "Única penthouse do edifício! 5 suítes com closets, living de 120m² com vista deslumbrante, cozinha gourmet integrada, home theater, lavabo social, terraço de 200m² com piscina privativa, sauna e espaço zen. 4 vagas de garagem. Luxo e exclusividade únicos!"
                price = 2500000.00
                image_url = $images[0]
            }
        )
    }
)

# Função para fazer login e obter token
function Get-AuthToken($email, $password) {
    $loginData = @{
        email = $email
        password = $password
    } | ConvertTo-Json
    
    try {
        $loginResponse = Invoke-RestMethod -Uri "http://localhost:8000/api/auth/login" -Method POST -ContentType "application/json" -Body $loginData
        return $loginResponse.data.token
    } catch {
        Write-Host "Erro ao fazer login para $email : $($_.Exception.Message)" -ForegroundColor Red
        return $null
    }
}

# Criando anúncios para cada usuário
foreach ($userData in $adsensesData) {
    Write-Host "`nCriando anúncios para: $($userData.user_email)" -ForegroundColor Cyan
    
    # Fazer login para obter o token
    $token = Get-AuthToken -email $userData.user_email -password "123456"
    if (-not $token) {
        Write-Host "Não foi possível obter token para $($userData.user_email)" -ForegroundColor Red
        continue
    }
    
    # Criar cada anúncio
    foreach ($adsense in $userData.adsenses) {
        Write-Host "  Criando: $($adsense.title)" -ForegroundColor Yellow
        
        $adsenseData = @{
            title = $adsense.title
            description = $adsense.description
            price = $adsense.price
        } | ConvertTo-Json
        
        try {
            $headers = @{
                "Authorization" = "Bearer $token"
                "Content-Type" = "application/json"
            }
            
            $adsenseResponse = Invoke-RestMethod -Uri "http://localhost:8000/api/adsense" -Method POST -Headers $headers -Body $adsenseData
            Write-Host "  ✓ Anúncio criado com sucesso! ID: $($adsenseResponse.data.id)" -ForegroundColor Green
            
            # Criar imagem para o anúncio
            if ($adsense.image_url) {
                $imageData = @{
                    url = $adsense.image_url
                    adsense_id = $adsenseResponse.data.id
                } | ConvertTo-Json
                
                try {
                    $imageResponse = Invoke-RestMethod -Uri "http://localhost:8000/api/images" -Method POST -Headers $headers -Body $imageData
                    Write-Host "  ✓ Imagem adicionada ao anúncio!" -ForegroundColor Green
                } catch {
                    Write-Host "  ✗ Erro ao adicionar imagem: $($_.Exception.Message)" -ForegroundColor Red
                }
            }
            
        } catch {
            Write-Host "  ✗ Erro ao criar anúncio: $($_.Exception.Message)" -ForegroundColor Red
        }
    }
}

Write-Host "`n🎉 Processo concluído!" -ForegroundColor Green
Write-Host "Verifique os dados criados em: http://localhost:8000/api/adsense/home" -ForegroundColor Blue
