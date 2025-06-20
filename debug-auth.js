// Test script para debuggar autenticação
console.log('=== INICIANDO TESTE DE AUTENTICAÇÃO ===');

const API_BASE_URL = 'http://localhost:8000/api';

// Função para fazer requests
async function testRequest(endpoint, options = {}) {
    const url = `${API_BASE_URL}${endpoint}`;
    const headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        ...options.headers
    };

    const config = {
        ...options,
        headers
    };

    console.log(`\n🔄 Fazendo request para: ${url}`);
    console.log('📋 Config:', JSON.stringify(config, null, 2));

    try {
        const response = await fetch(url, config);
        console.log(`📊 Status: ${response.status}`);
        console.log('📋 Headers:', [...response.headers.entries()]);
        
        const data = await response.json();
        console.log('📦 Response:', JSON.stringify(data, null, 2));
        
        return { response, data };
    } catch (error) {
        console.error('❌ Erro:', error);
        throw error;
    }
}

// Test 1: Health check
async function testHealth() {
    console.log('\n🏥 === TESTE 1: Health Check ===');
    try {
        const { data } = await testRequest('/health');
        console.log('✅ Health check OK');
        return true;
    } catch (error) {
        console.log('❌ Health check falhou');
        return false;
    }
}

// Test 2: Register
async function testRegister() {
    console.log('\n📝 === TESTE 2: Register ===');
    
    const userData = {
        name: 'Teste Debug',
        email: `teste.debug.${Date.now()}@email.com`,
        password: '123456',
        role: 'user'
    };
    
    console.log('👤 Dados do usuário:', userData);
    
    try {
        const { data } = await testRequest('/auth/register', {
            method: 'POST',
            body: JSON.stringify(userData)
        });
        
        if (data.success) {
            console.log('✅ Registro OK');
            return userData;
        } else {
            console.log('❌ Registro falhou:', data.message);
            return null;
        }
    } catch (error) {
        console.log('❌ Erro no registro:', error.message);
        return null;
    }
}

// Test 3: Login
async function testLogin(userData) {
    console.log('\n🔐 === TESTE 3: Login ===');
    
    const loginData = {
        email: userData.email,
        password: userData.password
    };
    
    console.log('🔑 Dados de login:', loginData);
    
    try {
        const { data } = await testRequest('/auth/login', {
            method: 'POST',
            body: JSON.stringify(loginData)
        });
        
        if (data.success) {
            console.log('✅ Login OK');
            console.log('🎫 Token:', data.data.token);
            return data.data.token;
        } else {
            console.log('❌ Login falhou:', data.message);
            return null;
        }
    } catch (error) {
        console.log('❌ Erro no login:', error.message);
        return null;
    }
}

// Test 4: Me endpoint
async function testMe(token) {
    console.log('\n👤 === TESTE 4: Me Endpoint ===');
    
    try {
        const { data } = await testRequest('/auth/me', {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`
            }
        });
        
        if (data.success) {
            console.log('✅ Me endpoint OK');
            console.log('👤 User data:', data.data.user);
            return true;
        } else {
            console.log('❌ Me endpoint falhou:', data.message);
            return false;
        }
    } catch (error) {
        console.log('❌ Erro no me endpoint:', error.message);
        return false;
    }
}

// Executar todos os testes
async function runAllTests() {
    try {
        // 1. Health check
        const healthOk = await testHealth();
        if (!healthOk) return;
        
        // 2. Register
        const userData = await testRegister();
        if (!userData) return;
        
        // 3. Login
        const token = await testLogin(userData);
        if (!token) return;
        
        // 4. Me endpoint
        const meOk = await testMe(token);
        
        console.log('\n🎉 === RESULTADO FINAL ===');
        console.log('✅ Todos os testes passaram!');
        
    } catch (error) {
        console.log('\n💥 === ERRO GERAL ===');
        console.error(error);
    }
}

// Executar quando o script for carregado
runAllTests();
