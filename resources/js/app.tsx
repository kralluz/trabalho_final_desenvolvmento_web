import React, { useState, useEffect } from 'react';
import { createRoot } from 'react-dom/client';
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
      <>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      <h1>teste</h1>
      </>
    );
};

export default App;

// Mount the React app to the DOM
const container = document.getElementById('app');
if (container) {
    const root = createRoot(container);
    root.render(<App />);
}