#!/usr/bin/env node

const fs = require('fs');
const { execSync } = require('child_process');
const path = require('path');

console.log('🚀 Laravel + React - Verificação Inteligente');
console.log('===========================================');

// Função para executar comandos
function runCommand(command, description) {
    try {
        console.log(`📋 ${description}...`);
        execSync(command, { stdio: 'inherit' });
        return true;
    } catch (error) {
        console.log(`❌ Erro: ${description}`);
        return false;
    }
}

// Função para verificar se arquivo/pasta existe
function exists(filePath) {
    return fs.existsSync(filePath);
}

// Verificar status do ambiente
const status = {
    vendor: exists('vendor'),
    nodeModules: exists('node_modules'),
    database: exists('database/database.sqlite'),
    env: exists('.env'),
    composerLock: exists('composer.lock'),
    packageLock: exists('package-lock.json')
};

console.log('\n📊 Status do Ambiente:');
console.log(`   Vendor (Composer): ${status.vendor ? '✅ OK' : '❌ PENDENTE'}`);
console.log(`   Node Modules:      ${status.nodeModules ? '✅ OK' : '❌ PENDENTE'}`);
console.log(`   Database SQLite:   ${status.database ? '✅ OK' : '❌ PENDENTE'}`);
console.log(`   Arquivo .env:      ${status.env ? '✅ OK' : '❌ PENDENTE'}`);

const needsSetup = !status.vendor || !status.nodeModules || !status.database || !status.env;

if (needsSetup) {
    console.log('\n⚙️ Preparando ambiente...');
    
    // Configurar .env
    if (!status.env) {
        console.log('📋 Copiando .env.example para .env...');
        fs.copyFileSync('.env.example', '.env');
        runCommand('php artisan key:generate --force', 'Gerando APP_KEY');
    }
    
    // Instalar dependências Composer
    if (!status.vendor) {
        if (!runCommand('composer install --optimize-autoloader --no-dev', 'Instalando dependências PHP')) {
            process.exit(1);
        }
    }
    
    // Instalar dependências NPM
    if (!status.nodeModules) {
        if (!runCommand('npm install', 'Instalando dependências Node.js')) {
            process.exit(1);
        }
    }
    
    // Configurar banco de dados
    if (!status.database) {
        console.log('🗄️ Criando banco de dados SQLite...');
        fs.writeFileSync('database/database.sqlite', '');
        runCommand('php artisan migrate --force', 'Executando migrations');
    }
    
    console.log('✅ Ambiente preparado!');
} else {
    console.log('\n✅ Ambiente já configurado!');
}

// Verificar migrations pendentes
console.log('\n🔍 Verificando migrations...');
try {
    const migrateStatus = execSync('php artisan migrate:status', { encoding: 'utf8' });
    if (migrateStatus.includes('Pending')) {
        console.log('⚠️ Migrations pendentes encontradas. Executando...');
        runCommand('php artisan migrate --force', 'Aplicando migrations');
    } else {
        console.log('✅ Migrations atualizadas!');
    }
} catch (error) {
    console.log('⚠️ Não foi possível verificar migrations');
}

// Limpar cache
console.log('\n🧹 Limpando cache...');
runCommand('php artisan config:clear', 'Cache de configuração');
runCommand('php artisan cache:clear', 'Cache da aplicação');

console.log('\n🎯 Pronto para iniciar!');
console.log('   Execute: npm run start');
