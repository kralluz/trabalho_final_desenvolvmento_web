import React, { useState, useEffect } from 'react';
import axios from 'axios';

// Configure axios defaults
axios.defaults.baseURL = 'http://localhost:8000';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['Accept'] = 'application/json';

interface ApiHealthResponse {
    status: string;
    message: string;
    timestamp: string;
}

const App: React.FC = () => {
    const [apiHealth, setApiHealth] = useState<ApiHealthResponse | null>(null);
    const [loading, setLoading] = useState<boolean>(true);
    const [error, setError] = useState<string | null>(null);

    useEffect(() => {
        checkApiHealth();
    }, []);

    const checkApiHealth = async () => {
        try {
            setLoading(true);
            const response = await axios.get<ApiHealthResponse>('/api/health');
            setApiHealth(response.data);
            setError(null);
        } catch (err) {
            setError('Erro ao conectar com a API');
            console.error('API Error:', err);
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
            <div className="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden">
                <div className="px-6 py-8">
                    <div className="text-center">
                        <h1 className="text-3xl font-bold text-gray-900 mb-6">
                            React + Laravel API
                        </h1>
                        
                        <div className="mb-6">
                            <h2 className="text-xl font-semibold text-gray-700 mb-4">
                                Status da API
                            </h2>
                            
                            {loading && (
                                <div className="flex justify-center">
                                    <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                                </div>
                            )}
                            
                            {error && (
                                <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                                    {error}
                                </div>
                            )}
                            
                            {apiHealth && (
                                <div className="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                                    <p><strong>Status:</strong> {apiHealth.status}</p>
                                    <p><strong>Mensagem:</strong> {apiHealth.message}</p>
                                    <p><strong>Timestamp:</strong> {new Date(apiHealth.timestamp).toLocaleString()}</p>
                                </div>
                            )}
                        </div>
                        
                        <button
                            onClick={checkApiHealth}
                            className="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-200"
                            disabled={loading}
                        >
                            {loading ? 'Verificando...' : 'Verificar API'}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default App;
