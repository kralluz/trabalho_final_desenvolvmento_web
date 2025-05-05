# Laravel + React Starter Kit

## Introduction

Our React starter kit provides a robust, modern starting point for building Laravel applications with a React frontend using [Inertia](https://inertiajs.com).

Inertia allows you to build modern, single-page React applications using classic server-side routing and controllers. This lets you enjoy the frontend power of React combined with the incredible backend productivity of Laravel and lightning-fast Vite compilation.

This React starter kit utilizes React 19, TypeScript, Tailwind, and the [shadcn/ui](https://ui.shadcn.com) and [radix-ui](https://www.radix-ui.com) component libraries.

## Official Documentation

Documentation for all Laravel starter kits can be found on the [Laravel website](https://laravel.com/docs/starter-kits).

## Contributing

Thank you for considering contributing to our starter kit! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## License

The Laravel + React starter kit is open-sourced software licensed under the MIT license.

# Laravel com React Router DOM e Inertia.js

Este projeto demonstra como usar React Router DOM com Laravel e Inertia.js, mantendo o Inertia.js apenas como fachada para a camada de servidor.

## Tecnologias utilizadas

- Laravel 12.8.0
- PHP 8.2.12
- Inertia.js
- React 19
- React Router DOM 7
- TailwindCSS

## Estrutura do projeto

O projeto utiliza duas abordagens:

1. **Inertia.js**: Para a navegação geral entre páginas principais e autenticação
2. **React Router DOM**: Para a navegação no frontend em componentes específicos

### Páginas Inertia

- `/` - Página inicial com React Router DOM integrado
- `/dashboard` - Dashboard do usuário (requer autenticação)
- `/api-exemplo` - Exemplo de uso da API com Inertia

### Rotas React Router

Dentro da aplicação React Router, temos as seguintes rotas:

- `/` - Página inicial do componente React
- `/api-test` - Teste de consumo da API REST
- `/about` - Página "Sobre"

## API REST

O projeto também inclui endpoints de API REST simples:

- `/api/info` - Retorna informações gerais da API
- `/api/recursos` - Retorna uma lista de recursos

## Como executar

1. Clone o repositório
2. Instale as dependências do PHP: `composer install`
3. Instale as dependências do Node.js: `npm install`
4. Configure o arquivo `.env` conforme necessário
5. Execute as migrações: `php artisan migrate`
6. Inicie o servidor: `npm run start`

O comando `npm run start` iniciará tanto o servidor Laravel quanto o servidor Vite em paralelo.

## Desenvolvimento

### Para adicionar novas rotas no React Router DOM

1. Abra o arquivo `resources/js/components/react-app.tsx`
2. Adicione um novo componente para sua página
3. Adicione a rota no componente `Routes`
4. Adicione o link na navegação

### Para adicionar novas rotas na API REST

1. Abra o arquivo `routes/api.php`
2. Adicione novas rotas usando `Route::get()` ou outros métodos HTTP

### Para consumir a API REST nos componentes React

Exemplo de consumo da API:

```tsx
const [data, setData] = useState(null);

useEffect(() => {
  fetch('/api/info')
    .then(response => response.json())
    .then(data => setData(data));
}, []);
```

## Configuração do Vite

O servidor Vite está configurado para usar `localhost` como host e configurar um proxy para as rotas `/api` para o servidor Laravel na porta 8000.
