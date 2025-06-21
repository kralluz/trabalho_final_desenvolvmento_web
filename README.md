# 🎓 Contexto Acadêmico

**Curso:** Bacharelado em Sistemas de Informação  
**Disciplina:** Desenvolvimento Web II  
**Semestre:** 2025/01

**Equipe:**

- Carlos Henrique Alves
- Felipe Gomes
- Iago José
- Victor Augusto

---

## 👥 Responsabilidades da Equipe

- **Carlos Henrique Alves**: Responsável por organizar e separar a equipe em áreas como banco de dados e modelagem das classes model. Desenvolveu todo o backend, auxiliou Felipe Gomes no frontend e implementou a integração com o serviço de hospedagem de imagens Cloudinary.
- **Victor Augusto**: Executor das tarefas e demandas do projeto.
- **Felipe Gomes**: Programou o frontend em conjunto com Iago José.
- **Iago José**: Responsável pelo design e concepção da interface, além de colaborar no desenvolvimento do frontend.

---


# Domus

Domus é uma plataforma web para divulgação de casas para aluguel e venda.

O objetivo do projeto é facilitar a conexão entre proprietários, imobiliárias e pessoas interessadas em encontrar imóveis residenciais, oferecendo uma experiência moderna, rápida e intuitiva.

---

**Tecnologias:** Laravel 12, React 19, TypeScript, Vite, Tailwind, Inertia.js e React Router DOM.

---

---

## 🚀 Como rodar o projeto do zero

### Pré-requisitos

- PHP 8.2 ou superior
- Composer
- Node.js (recomendado 18+)
- NPM

### Passo a passo rápido

1. **Clone o repositório:**
   ```bash
   git clone https://github.com/kralluz/trabvirso.git
   cd trabvirso
   ```

2. **Instale as dependências e prepare o ambiente:**
   ```bash
   npm run setup
   ```
   > Isso instala dependências do PHP e Node, cria o banco SQLite e executa as migrações.

3. **Inicie o projeto:**
   ```bash
   npm run start
   ```
   > Isso sobe o servidor Laravel (porta 8000) e o Vite (porta 5173) juntos.

Pronto! Acesse [http://localhost:5173](http://localhost:5173) para usar o frontend React.

---

## 🛠️ Scripts úteis

| Comando              | O que faz                                      |
|----------------------|------------------------------------------------|
| npm run setup        | Instala tudo e prepara o banco                 |
| npm run start        | Sobe Laravel + Vite juntos                     |
| npm run serve        | Sobe só o backend Laravel                      |
| npm run dev          | Sobe só o frontend React/Vite                  |
| npm run prepare-db   | Cria banco SQLite e executa migrações          |
| npm run reset-db     | Reseta o banco de dados                        |
| npm run build        | Compila assets para produção                   |
| npm run clear        | Limpa caches do Laravel                        |

---

## 📝 Configuração manual (opcional)

Se preferir, pode rodar cada etapa manualmente:

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
npm run dev
php artisan serve
```

---

## 📦 Tecnologias principais

- Laravel 12
- React 19 + TypeScript
- Vite
- TailwindCSS
- Inertia.js
- React Router DOM 7
- SQLite (banco de dados)

---

## 🗂️ Estrutura do projeto

- Backend: pasta raiz padrão Laravel
- Frontend: `resources/js/`
- Rotas React: `AppRoutes.tsx`
- Rotas API: `routes/api.php`
- Rotas web: `routes/web.php`

---
---
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
   git clone https://github.com/kralluz/trabalho_final_desenvolvmento_web
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
