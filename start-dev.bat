@echo off
echo Configurando ambiente de desenvolvimento...

REM Configurar PATH para usar PHP 8.4.8 do Laragon
set PATH=C:\laragon\bin\php\php-8.4.8;%PATH%

echo Verificando versão do PHP...
php --version

echo Iniciando servidores...
npm run start
