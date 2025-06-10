# 🚀 Script Inteligente de Desenvolvimento - Laravel + React
# Verifica e prepara automaticamente o ambiente de desenvolvimento

param(
    [switch]$Force,  # Força reinstalação completa
    [switch]$Fresh   # Recria banco de dados
)

Write-Host "Laravel + React - Inicializacao Inteligente" -ForegroundColor Green
Write-Host "=============================================" -ForegroundColor Green

# Configurar PATH para PHP 8.4.8 do Laragon
$env:PATH = "C:\laragon\bin\php\php-8.4.8;$env:PATH"

# Função para verificar status do ambiente
function Test-Environment {
    $status = @{
        VendorExists = Test-Path "vendor"
        NodeModulesExists = Test-Path "node_modules"
        DatabaseExists = Test-Path "database/database.sqlite"
        EnvExists = Test-Path ".env"
        ComposerLock = Test-Path "composer.lock"
        PackageLock = Test-Path "package-lock.json"
    }
    
    $status.NeedsComposer = -not $status.VendorExists -or -not $status.ComposerLock
    $status.NeedsNpm = -not $status.NodeModulesExists -or -not $status.PackageLock
    $status.NeedsDatabase = -not $status.DatabaseExists
    $status.NeedsEnv = -not $status.EnvExists
    $status.IsFirstRun = $status.NeedsComposer -or $status.NeedsNpm -or $status.NeedsDatabase -or $status.NeedsEnv
    
    return $status
}

# Função para verificar migrations pendentes
function Test-MigrationStatus {
    try {
        $output = php artisan migrate:status 2>&1
        return $output -match "Pending"
    } catch {
        return $true # Se não conseguir verificar, assume que precisa migrar
    }
}

# Verificar status do ambiente
$env = Test-Environment

if ($Force) {
    Write-Host "Modo FORCE ativado - Reinstalando tudo..." -ForegroundColor Yellow
    $env.NeedsComposer = $true
    $env.NeedsNpm = $true
    $env.IsFirstRun = $true
}

if ($Fresh) {
    Write-Host "Modo FRESH ativado - Recriando banco de dados..." -ForegroundColor Yellow
    $env.NeedsDatabase = $true
}

# Mostrar status atual
Write-Host ""
Write-Host "Status do Ambiente:" -ForegroundColor Cyan
Write-Host "   Vendor (Composer): " -NoNewline
if ($env.VendorExists -and -not $env.NeedsComposer) { Write-Host "OK" -ForegroundColor Green } else { Write-Host "PENDENTE" -ForegroundColor Red }
Write-Host "   Node Modules:      " -NoNewline  
if ($env.NodeModulesExists -and -not $env.NeedsNpm) { Write-Host "OK" -ForegroundColor Green } else { Write-Host "PENDENTE" -ForegroundColor Red }
Write-Host "   Database SQLite:   " -NoNewline
if ($env.DatabaseExists -and -not $env.NeedsDatabase) { Write-Host "OK" -ForegroundColor Green } else { Write-Host "PENDENTE" -ForegroundColor Red }
Write-Host "   Arquivo .env:      " -NoNewline
if ($env.EnvExists) { Write-Host "OK" -ForegroundColor Green } else { Write-Host "PENDENTE" -ForegroundColor Red }

# Preparar ambiente se necessário
if ($env.IsFirstRun -or $env.NeedsDatabase) {
    Write-Host ""
    Write-Host "Preparando ambiente de desenvolvimento..." -ForegroundColor Yellow
    
    # 1. Configurar .env
    if ($env.NeedsEnv) {
        Write-Host "Copiando .env.example para .env..." -ForegroundColor Blue
        Copy-Item ".env.example" ".env" -ErrorAction SilentlyContinue
        
        Write-Host "Gerando APP_KEY..." -ForegroundColor Blue
        php artisan key:generate --force
    }
      # 2. Instalar dependências Composer
    if ($env.NeedsComposer) {
        Write-Host "Instalando dependencias PHP (Composer)..." -ForegroundColor Blue
        composer install --optimize-autoloader --no-dev
        if ($LASTEXITCODE -ne 0) {
            Write-Host "Erro ao instalar dependencias PHP" -ForegroundColor Red
            exit 1
        }
    }
    
    # 3. Instalar dependências NPM
    if ($env.NeedsNpm) {
        Write-Host "Instalando dependencias Node.js (NPM)..." -ForegroundColor Blue
        npm install
        if ($LASTEXITCODE -ne 0) {
            Write-Host "Erro ao instalar dependencias Node.js" -ForegroundColor Red
            exit 1
        }
    }
      # 4. Configurar banco de dados
    if ($env.NeedsDatabase -or $Fresh) {
        Write-Host "Configurando banco de dados SQLite..." -ForegroundColor Blue
        
        if ($Fresh -and (Test-Path "database/database.sqlite")) {
            Remove-Item "database/database.sqlite" -Force
        }
        
        if (-not (Test-Path "database/database.sqlite")) {
            New-Item -ItemType File -Path "database/database.sqlite" -Force | Out-Null
        }
        
        Write-Host "Executando migrations..." -ForegroundColor Blue
        if ($Fresh) {
            php artisan migrate:fresh --seed --force
        } else {
            php artisan migrate --force
        }
        
        if ($LASTEXITCODE -ne 0) {
            Write-Host "Algumas migrations podem ter falhado" -ForegroundColor Yellow
        }
    }
    
    Write-Host "Ambiente preparado com sucesso!" -ForegroundColor Green
} else {
    Write-Host ""
    Write-Host "Ambiente ja configurado!" -ForegroundColor Green
}

# Verificar migrations pendentes
Write-Host ""
Write-Host "Verificando migrations pendentes..." -ForegroundColor Blue
if (Test-MigrationStatus) {
    Write-Host "Existem migrations pendentes. Executando..." -ForegroundColor Yellow
    php artisan migrate --force
} else {
    Write-Host "Todas as migrations estao atualizadas!" -ForegroundColor Green
}

# Limpar cache
Write-Host ""
Write-Host "Limpando cache..." -ForegroundColor Blue
php artisan config:clear | Out-Null
php artisan cache:clear | Out-Null
php artisan view:clear | Out-Null
php artisan route:clear | Out-Null

# Verificar versão do PHP
Write-Host ""
Write-Host "Versao do PHP:" -ForegroundColor Blue
php --version | Select-Object -First 1

# Iniciar servidores
Write-Host ""
Write-Host "Iniciando servidores de desenvolvimento..." -ForegroundColor Cyan
Write-Host "   Laravel: http://localhost:8000" -ForegroundColor White
Write-Host "   Vite: http://localhost:5173" -ForegroundColor White
Write-Host ""
Write-Host "Para parar os servidores: Ctrl+C" -ForegroundColor DarkGray
Write-Host "Para ver logs: observe os terminais abaixo" -ForegroundColor DarkGray
Write-Host ""

# Executar servidores
npm run start