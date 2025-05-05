import React from 'react';
import { Head } from '@inertiajs/react';
import { ApiExample } from '@/components/api-example';
import AppLayout from '@/layouts/app-layout';

export default function ApiExamplePage() {
    return (
        <>
            <Head title="Exemplo de API" />
            <div className="py-6">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <h1 className="text-2xl font-semibold mb-6">
                        Exemplo de Integração com API REST
                    </h1>
                    <p className="mb-6">
                        Esta página demonstra como o Inertia.js e as chamadas de API REST podem coexistir em um único aplicativo.
                        O Inertia.js é usado para a navegação e o gerenciamento de estado global, enquanto as chamadas de API REST
                        são usadas para buscar dados específicos de forma assíncrona.
                    </p>
                    <ApiExample />
                </div>
            </div>
        </>
    );
}

ApiExamplePage.layout = (page: React.ReactNode) => <AppLayout children={page} />; 