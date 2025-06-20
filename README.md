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

## Installation

1. **Clone o repositório:**
```bash
git clone <repository-url>
cd trabvirso
```

2. **Instale as dependências:**
```bash
composer install
npm install
```

3. **Configure o ambiente:**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure o banco de dados:**
```bash
php artisan migrate
```

5. **Compile os assets:**
```bash
npm run dev
```

6. **Inicie o servidor:**
```bash
php artisan serve
```

**📝 NOTA:** O banco iniciará vazio. Você pode criar sua conta pelo frontend e começar a usar a aplicação normalmente.

## Tecnologias utilizadas

- Laravel 12.8.0
- PHP 8.2+
- SQLite (banco de dados)
- Inertia.js
- React 19
- React Router DOM 7
- TailwindCSS
- Vite

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

## Configuração e Execução Rápida

Para configurar e executar o projeto rapidamente, usamos scripts NPM para automatizar o processo:

```bash
# Configuração completa (instala dependências e prepara o banco de dados)
npm run setup

# OU configure manualmente
composer install
npm install
npm run prepare-db  # Cria o banco SQLite e executa as migrações

# Inicia o servidor de desenvolvimento
npm run start
```

O comando `npm run start` iniciará tanto o servidor Laravel (porta 8000) quanto o servidor Vite em paralelo.

## Configuração Detalhada

### Pré-requisitos

- PHP 8.2 ou superior
- Composer
- Node.js e NPM

### Passo a Passo

1. **Clone o repositório:**
   ```bash
   git clone [URL_DO_REPOSITÓRIO]
   cd trabvirso
   ```

2. **Configuração do ambiente:**
   - Copie o arquivo `.env.example` para `.env`:
     ```bash
     cp .env.example .env
     ```
   - Gere uma chave da aplicação (já configurada no repositório):
     ```bash
     php artisan key:generate
     ```

3. **Configuração do banco de dados:**
   - Por padrão, usamos SQLite para facilitar a configuração
   - O arquivo do banco de dados será criado automaticamente em `database/database.sqlite`

4. **Instalação de dependências e configuração do banco:**
   ```bash
   npm run setup
   ```
   
   Este comando realiza as seguintes ações:
   - Instala dependências do Composer
   - Instala dependências do NPM
   - Cria o arquivo do banco de dados SQLite
   - Executa as migrações do banco de dados

5. **Iniciar o servidor:**
   ```bash
   npm run start
   ```

## Scripts Disponíveis

O projeto inclui vários scripts úteis no `package.json`:

| Comando | Descrição |
|---------|-----------|
| `npm run setup` | Instalação completa (dependências + banco de dados) |
| `npm run prepare-db` | Cria o banco de dados SQLite e executa migrações |
| `npm run reset-db` | Reseta o banco de dados (apaga tudo e recria) |
| `npm run start` | Inicia o servidor Laravel e Vite em paralelo |
| `npm run serve` | Inicia apenas o servidor Laravel |
| `npm run dev` | Inicia apenas o servidor Vite |
| `npm run build` | Compila os assets para produção |
| `npm run clear` | Limpa os caches do Laravel |

## Desenvolvimento

### Banco de Dados

O projeto usa SQLite para facilitar o desenvolvimento. O arquivo do banco de dados é criado em:
```
database/database.sqlite
```

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

## Solução de Problemas

### Erro de Banco de Dados

Se você encontrar erros relacionados ao banco de dados SQLite:

```
Database file at path [database/database.sqlite] does not exist
```

Execute o comando:
```bash
npm run prepare-db
```

### Porta em uso

Se o servidor Laravel não conseguir iniciar devido a uma porta já em uso, você pode mudar a porta manualmente:

```bash
php artisan serve --port=8001
```

## Configuração do Vite

O servidor Vite está configurado para usar `localhost` como host e configurar um proxy para as rotas `/api` para o servidor Laravel na porta 8000.
