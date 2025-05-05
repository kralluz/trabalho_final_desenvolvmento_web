import React, { useState, useEffect } from 'react';

interface ApiResource {
    id: number;
    nome: string;
    descricao: string;
}

interface ApiInfo {
    message: string;
    versao: string;
    status: string;
    timestamp: string;
}

export function ApiExample() {
    const [info, setInfo] = useState<ApiInfo | null>(null);
    const [recursos, setRecursos] = useState<ApiResource[]>([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);

    useEffect(() => {
        // Fetch API info
        fetch('/api/info')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Falha ao buscar informações da API');
                }
                return response.json();
            })
            .then(data => {
                setInfo(data);
                // Depois de carregar as informações, buscar os recursos
                return fetch('/api/recursos');
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Falha ao buscar recursos da API');
                }
                return response.json();
            })
            .then(data => {
                setRecursos(data.recursos);
                setLoading(false);
            })
            .catch(err => {
                setError(err.message);
                setLoading(false);
            });
    }, []);

    if (loading) {
        return <div className="p-4">Carregando dados da API...</div>;
    }

    if (error) {
        return <div className="p-4 text-red-500">Erro: {error}</div>;
    }

    return (
        <div className="p-4 space-y-6">
            <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <h2 className="text-xl font-semibold mb-2">Informações da API</h2>
                {info && (
                    <div className="space-y-1">
                        <p><span className="font-medium">Mensagem:</span> {info.message}</p>
                        <p><span className="font-medium">Versão:</span> {info.versao}</p>
                        <p><span className="font-medium">Status:</span> {info.status}</p>
                        <p><span className="font-medium">Timestamp:</span> {info.timestamp}</p>
                    </div>
                )}
            </div>

            <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <h2 className="text-xl font-semibold mb-4">Recursos da API</h2>
                <div className="space-y-4">
                    {recursos.map(recurso => (
                        <div key={recurso.id} className="border-b pb-2 last:border-0">
                            <h3 className="text-lg font-medium">{recurso.nome}</h3>
                            <p className="text-gray-600 dark:text-gray-300">{recurso.descricao}</p>
                        </div>
                    ))}
                </div>
            </div>

            <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <h2 className="text-xl font-semibold mb-2">Exemplo de Uso da API REST</h2>
                <p className="mb-2">Este componente demonstra como consumir a API REST do Laravel diretamente do React,
                   mesmo mantendo a integração com o Inertia.js.</p>
                <p className="font-mono bg-gray-100 dark:bg-gray-700 p-2 rounded text-sm">
                    fetch(&#39;/api/recursos&#39;).then(res =&gt; res.json()).then(data =&gt; console.log(data))
                </p>
            </div>
        </div>
    );
} 