import React, { useState, useEffect } from 'react';
import axios from 'axios';

// Configure axios defaults for Laravel API
axios.defaults.baseURL = 'http://localhost:8000';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['Accept'] = 'application/json';
axios.defaults.headers.common['Content-Type'] = 'application/json';

interface ApiHealthResponse {
    status: string;
    message: string;
    timestamp: string;
    version: string;
    environment: string;
}

interface ApiDocsResponse {
    titulo: string;
    versao: string;
    descricao: string;
    url_base: string;
    endpoints: Array<{
        metodo: string;
        caminho: string;
        descricao: string;
        parametros: any[];
        exemplo_resposta: any;
    }>;
}

interface User {
    id: number;
    name: string;
    email: string;
    created_at: string;
}

const App: React.FC = () => {
    const [apiHealth, setApiHealth] = useState<ApiHealthResponse | null>(null);
    const [apiDocs, setApiDocs] = useState<ApiDocsResponse | null>(null);
    const [users, setUsers] = useState<User[]>([]);
    const [loading, setLoading] = useState<boolean>(false);
    const [error, setError] = useState<string | null>(null);
    const [activeTab, setActiveTab] = useState<'health' | 'docs' | 'users'>('health');

    useEffect(() => {
        checkApiHealth();
    }, []);

    const checkApiHealth = async () => {
        try {
            setLoading(true);
            setError(null);
            const response = await axios.get<ApiHealthResponse>('/api/health');
            setApiHealth(response.data);
        } catch (err: any) {
            setError(`Erro ao conectar com a API: ${err.message}`);
            console.error('API Health Error:', err);
        } finally {
            setLoading(false);
        }
    };

    const fetchApiDocs = async () => {
        try {
            setLoading(true);
            setError(null);
            const response = await axios.get<ApiDocsResponse>('/api/docs');
            setApiDocs(response.data);
        } catch (err: any) {
            setError(`Erro ao buscar documentação: ${err.message}`);
            console.error('API Docs Error:', err);
        } finally {
            setLoading(false);
        }
    };

    const fetchUsers = async () => {
        try {
            setLoading(true);
            setError(null);
            const response = await axios.get<User[]>('/api/users');
            setUsers(response.data);
        } catch (err: any) {
            setError(`Erro ao buscar usuários: ${err.message}`);
            console.error('API Users Error:', err);
        } finally {
            setLoading(false);
        }
    };

    const handleTabClick = (tab: 'health' | 'docs' | 'users') => {
        setActiveTab(tab);
        setError(null);
        
        switch (tab) {
            case 'health':
                checkApiHealth();
                break;
            case 'docs':
                fetchApiDocs();
                break;
            case 'users':
                fetchUsers();
                break;
        }
    };

    return (
        <div className="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8 px-4 sm:px-6 lg:px-8">
            <div className="max-w-4xl mx-auto">
                <div className="bg-white rounded-lg shadow-lg overflow-hidden">
                    {/* Header */}
                    <div className="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-8">
                        <h1 className="text-3xl font-bold text-white text-center">
                            🚀 React + Laravel API Test Suite
                        </h1>
                        <p className="text-blue-100 text-center mt-2">
                            Componente de teste para consumo da API REST
                        </p>
                    </div>

                    {/* Tabs */}
                    <div className="border-b border-gray-200">
                        <nav className="-mb-px flex">
                            {[
                                { key: 'health', label: '🏥 Health Check', desc: 'Status da API' },
                                { key: 'docs', label: '📚 Documentação', desc: 'Endpoints da API' },
                                { key: 'users', label: '👥 Usuários', desc: 'Listar usuários' }
                            ].map((tab) => (
                                <button
                                    key={tab.key}
                                    onClick={() => handleTabClick(tab.key as any)}
                                    className={`flex-1 py-4 px-6 text-center border-b-2 font-medium text-sm transition-colors ${
                                        activeTab === tab.key
                                            ? 'border-blue-500 text-blue-600 bg-blue-50'
                                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                                    }`}
                                >
                                    <div>{tab.label}</div>
                                    <div className="text-xs text-gray-400 mt-1">{tab.desc}</div>
                                </button>
                            ))}
                        </nav>
                    </div>

                    {/* Content */}
                    <div className="p-6">
                        {/* Loading State */}
                        {loading && (
                            <div className="flex justify-center items-center py-8">
                                <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                                <span className="ml-2 text-gray-600">Carregando...</span>
                            </div>
                        )}

                        {/* Error State */}
                        {error && (
                            <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                                <strong>Erro:</strong> {error}
                            </div>
                        )}

                        {/* Health Check Tab */}
                        {activeTab === 'health' && apiHealth && !loading && (
                            <div className="space-y-4">
                                <div className="bg-green-50 border border-green-200 rounded-lg p-4">
                                    <h3 className="text-lg font-semibold text-green-800 mb-3">✅ API Status</h3>
                                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                        <div><strong>Status:</strong> <span className="text-green-600">{apiHealth.status}</span></div>
                                        <div><strong>Environment:</strong> {apiHealth.environment}</div>
                                        <div><strong>Version:</strong> {apiHealth.version}</div>
                                        <div><strong>Timestamp:</strong> {new Date(apiHealth.timestamp).toLocaleString()}</div>
                                    </div>
                                    <p className="mt-3 text-green-700">{apiHealth.message}</p>
                                </div>
                            </div>
                        )}

                        {/* Documentation Tab */}
                        {activeTab === 'docs' && apiDocs && !loading && (
                            <div className="space-y-6">
                                <div className="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <h3 className="text-lg font-semibold text-blue-800">{apiDocs.titulo}</h3>
                                    <p className="text-blue-600 mt-1">{apiDocs.descricao}</p>
                                    <div className="mt-3 text-sm text-blue-700">
                                        <strong>Versão:</strong> {apiDocs.versao} | <strong>Base URL:</strong> {apiDocs.url_base}
                                    </div>
                                </div>
                                
                                <div className="grid gap-4">
                                    {apiDocs.endpoints.slice(0, 5).map((endpoint, index) => (
                                        <div key={index} className="bg-gray-50 border rounded-lg p-4">
                                            <div className="flex items-center gap-3 mb-2">
                                                <span className={`px-2 py-1 rounded text-xs font-bold ${
                                                    endpoint.metodo === 'GET' ? 'bg-green-100 text-green-800' :
                                                    endpoint.metodo === 'POST' ? 'bg-blue-100 text-blue-800' :
                                                    'bg-gray-100 text-gray-800'
                                                }`}>
                                                    {endpoint.metodo}
                                                </span>
                                                <code className="text-sm font-mono">{endpoint.caminho}</code>
                                            </div>
                                            <p className="text-gray-600 text-sm">{endpoint.descricao}</p>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        )}

                        {/* Users Tab */}
                        {activeTab === 'users' && !loading && (
                            <div className="space-y-4">
                                {users.length > 0 ? (
                                    <div className="bg-gray-50 border rounded-lg overflow-hidden">
                                        <div className="bg-gray-100 px-4 py-3 border-b">
                                            <h3 className="text-lg font-semibold text-gray-800">👥 Usuários ({users.length})</h3>
                                        </div>
                                        <div className="divide-y divide-gray-200">
                                            {users.map((user) => (
                                                <div key={user.id} className="px-4 py-3 hover:bg-gray-50">
                                                    <div className="flex justify-between items-start">
                                                        <div>
                                                            <h4 className="font-medium text-gray-900">{user.name}</h4>
                                                            <p className="text-sm text-gray-600">{user.email}</p>
                                                        </div>
                                                        <div className="text-right text-xs text-gray-500">
                                                            ID: {user.id}<br/>
                                                            {new Date(user.created_at).toLocaleDateString()}
                                                        </div>
                                                    </div>
                                                </div>
                                            ))}
                                        </div>
                                    </div>
                                ) : (
                                    <div className="text-center py-8 text-gray-500">
                                        <p>Nenhum usuário encontrado</p>
                                        <p className="text-sm mt-2">A API pode não ter dados ou o endpoint pode não estar disponível</p>
                                    </div>
                                )}
                            </div>
                        )}

                        {/* Refresh Button */}
                        <div className="mt-6 text-center">
                            <button
                                onClick={() => handleTabClick(activeTab)}
                                className="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg focus:outline-none focus:shadow-outline transition duration-200 disabled:opacity-50"
                                disabled={loading}
                            >
                                {loading ? 'Carregando...' : '🔄 Atualizar'}
                            </button>
                        </div>
                    </div>
                </div>

                {/* Footer Info */}
                <div className="mt-6 text-center text-sm text-gray-600">
                    <p>🔧 <strong>Pure React</strong> consumindo <strong>Laravel API REST</strong> via <strong>Axios HTTP</strong></p>
                    <p className="mt-1">Sem Inertia.js - Comunicação 100% via API REST</p>
                </div>
            </div>
        </div>
    );
};

export default App;
