# Script inteligente para iniciar ambiente de desenvolvimento
Write-Host "🚀 Verificando ambiente de desenvolvimento..." -ForegroundColor Green

# Configurar PATH para usar PHP 8.4.8 do Laragon
$env:PATH = "C:\laragon\bin\php\php-8.4.8;$env:PATH"

# Função para verificar se precisa de setup
function Test-Environment {
    $vendorExists = Test-Path "vendor"
    $nodeModulesExists = Test-Path "node_modules"
    $dbExists = Test-Path "database/database.sqlite"
    $envExists = Test-Path ".env"
    
    return @{
        VendorExists = $vendorExists
        NodeModulesExists = $nodeModulesExists
        DatabaseExists = $dbExists
        EnvExists = $envExists
        NeedsSetup = -not ($vendorExists -and $nodeModulesExists -and $dbExists -and $envExists)
    }
}

# Verificar ambiente
$env = Test-Environment

if ($env.NeedsSetup) {
    Write-Host "⚙️  Primeiro setup detectado. Preparando ambiente..." -ForegroundColor Yellow
    
    # Verificar .env
    if (-not $env.EnvExists) {
        Write-Host "📋 Copiando .env.example para .env..." -ForegroundColor Blue
        Copy-Item ".env.example" ".env" -ErrorAction SilentlyContinue
        Write-Host "🔑 Gerando APP_KEY..." -ForegroundColor Blue
        php artisan key:generate
    }
    
    # Verificar Composer
    if (-not $env.VendorExists) {
        Write-Host "📦 Instalando dependências PHP (Composer)..." -ForegroundColor Blue
        composer install --no-dev
    }
    
    # Verificar NPM
    if (-not $env.NodeModulesExists) {
        Write-Host "📦 Instalando dependências Node.js (NPM)..." -ForegroundColor Blue
        npm install
    }
    
    # Verificar Database
    if (-not $env.DatabaseExists) {
        Write-Host "🗄️  Criando banco de dados SQLite..." -ForegroundColor Blue
        New-Item -ItemType File -Path "database/database.sqlite" -Force | Out-Null
        Write-Host "🔄 Executando migrations..." -ForegroundColor Blue
        php artisan migrate --force
    }
    
    Write-Host "✅ Setup concluído!" -ForegroundColor Green
} else {
    Write-Host "✅ Ambiente já configurado!" -ForegroundColor Green
}

Write-Host "📋 Verificando versão do PHP..." -ForegroundColor Blue
php --version

Write-Host "🔄 Limpando cache do Laravel..." -ForegroundColor Yellow
php artisan config:clear | Out-Null
php artisan cache:clear | Out-Null

Write-Host "🎯 Iniciando servidores..." -ForegroundColor Cyan
Write-Host "   - Laravel: http://localhost:8000" -ForegroundColor White
Write-Host "   - Vite: http://localhost:5173 (ou próxima porta disponível)" -ForegroundColor White
Write-Host "" 
Write-Host "💡 Para parar os servidores, pressione Ctrl+C" -ForegroundColor DarkGray

npm run start
