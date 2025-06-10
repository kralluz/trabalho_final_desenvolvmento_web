@echo off
chcp 65001 >nul
echo 🚀 Verificando ambiente de desenvolvimento...

REM Configurar PATH para usar PHP 8.4.8 do Laragon
set PATH=C:\laragon\bin\php\php-8.4.8;%PATH%

REM Verificar se precisa de setup
set NEEDS_SETUP=0

if not exist "vendor" (
    echo ⚠️  Pasta vendor não encontrada
    set NEEDS_SETUP=1
)

if not exist "node_modules" (
    echo ⚠️  Pasta node_modules não encontrada
    set NEEDS_SETUP=1
)

if not exist "database\database.sqlite" (
    echo ⚠️  Banco de dados não encontrado
    set NEEDS_SETUP=1
)

if not exist ".env" (
    echo ⚠️  Arquivo .env não encontrado
    set NEEDS_SETUP=1
)

if %NEEDS_SETUP%==1 (
    echo.
    echo ⚙️  Primeiro setup detectado. Preparando ambiente...
    
    if not exist ".env" (
        echo 📋 Copiando .env.example para .env...
        copy ".env.example" ".env" >nul
        echo 🔑 Gerando APP_KEY...
        php artisan key:generate
    )
    
    if not exist "vendor" (
        echo 📦 Instalando dependências PHP (Composer)...
        composer install --no-dev
    )
    
    if not exist "node_modules" (
        echo 📦 Instalando dependências Node.js (NPM)...
        npm install
    )
    
    if not exist "database\database.sqlite" (
        echo 🗄️  Criando banco de dados SQLite...
        type nul > "database\database.sqlite"
        echo 🔄 Executando migrations...
        php artisan migrate --force
    )
    
    echo ✅ Setup concluído!
) else (
    echo ✅ Ambiente já configurado!
)

echo.
echo 📋 Verificando versão do PHP...
php --version

echo.
echo 🔄 Limpando cache do Laravel...
php artisan config:clear >nul
php artisan cache:clear >nul

echo.
echo 🎯 Iniciando servidores...
echo    - Laravel: http://localhost:8000
echo    - Vite: http://localhost:5173 (ou próxima porta disponível)
echo.
echo 💡 Para parar os servidores, pressione Ctrl+C
echo.

npm run start
