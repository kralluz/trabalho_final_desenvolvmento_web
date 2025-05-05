import React, { useState, useEffect } from 'react';
import { BrowserRouter as Router, Routes, Route, Link } from 'react-router-dom';

// Página inicial
const Home = () => {
  return (
    <div className="p-6">
      <h1 className="text-2xl font-bold mb-4">React Router DOM - Página Inicial</h1>
      <p className="mb-4">Esta é uma aplicação React com React Router DOM, desacoplada do Inertia.js</p>
      <div className="space-y-2">
        <Link to="/" className="text-blue-500 hover:underline block">Página Inicial</Link>
        <Link to="/api-test" className="text-blue-500 hover:underline block">Teste da API</Link>
        <Link to="/about" className="text-blue-500 hover:underline block">Sobre</Link>
      </div>
    </div>
  );
};

// Página de teste da API
const ApiTest = () => {
  const [info, setInfo] = useState<any>(null);
  const [recursos, setRecursos] = useState<any[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    // Função para buscar os dados da API
    const fetchData = async () => {
      try {
        setLoading(true);
        
        // Buscar informações da API
        const infoResponse = await fetch('/api/info');
        if (!infoResponse.ok) {
          throw new Error('Falha ao buscar informações da API');
        }
        const infoData = await infoResponse.json();
        setInfo(infoData);
        
        // Buscar recursos da API
        const recursosResponse = await fetch('/api/recursos');
        if (!recursosResponse.ok) {
          throw new Error('Falha ao buscar recursos da API');
        }
        const recursosData = await recursosResponse.json();
        setRecursos(recursosData.recursos);
        
        setLoading(false);
      } catch (err: any) {
        setError(err.message);
        setLoading(false);
      }
    };

    fetchData();
  }, []);

  if (loading) {
    return <div className="p-6">Carregando dados da API...</div>;
  }

  if (error) {
    return <div className="p-6 text-red-500">Erro: {error}</div>;
  }

  return (
    <div className="p-6 space-y-6">
      <h1 className="text-2xl font-bold mb-4">Teste da API REST</h1>
      
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

      <div className="mt-4">
        <Link to="/" className="text-blue-500 hover:underline">← Voltar para a página inicial</Link>
      </div>
    </div>
  );
};

// Página Sobre
const About = () => {
  return (
    <div className="p-6">
      <h1 className="text-2xl font-bold mb-4">Sobre</h1>
      <p className="mb-4">Este é um projeto que utiliza React Router DOM para gerenciar rotas no frontend, enquanto mantém o Inertia.js apenas como uma fachada.</p>
      <p className="mb-4">A API REST do Laravel é consumida diretamente pelos componentes React.</p>
      <div className="mt-4">
        <Link to="/" className="text-blue-500 hover:underline">← Voltar para a página inicial</Link>
      </div>
    </div>
  );
};

// Componente principal da aplicação React
export default function ReactApp() {
  return (
    <Router>
      <div className="container mx-auto py-8">
        <div className="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
          <div className="bg-gradient-to-r from-blue-500 to-indigo-600 p-4 text-white">
            <h2 className="text-xl font-bold">React Router DOM + Laravel API</h2>
            <p className="text-sm opacity-80">Navegação no frontend com React Router DOM</p>
          </div>
          
          <nav className="bg-gray-100 dark:bg-gray-700 p-4 border-b border-gray-200 dark:border-gray-600">
            <div className="flex space-x-4">
              <Link to="/" className="px-3 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-600">Página Inicial</Link>
              <Link to="/api-test" className="px-3 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-600">Teste da API</Link>
              <Link to="/about" className="px-3 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-600">Sobre</Link>
            </div>
          </nav>
          
          <div className="p-4">
            <Routes>
              <Route path="/" element={<Home />} />
              <Route path="/api-test" element={<ApiTest />} />
              <Route path="/about" element={<About />} />
            </Routes>
          </div>
        </div>
      </div>
    </Router>
  );
} 