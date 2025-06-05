# Documentação das Rotas da API REST

Este documento descreve todas as rotas disponíveis na API REST, seus parâmetros, solicitações e respostas.

## Rotas Públicas

### Informações da API

```
GET /api/info
```

**Resposta (200 OK)**
```json
{
    "message": "API funcionando corretamente",
    "versao": "1.0",
    "status": "online",
    "timestamp": "2024-01-01T12:00:00Z"
}
```

### Recursos Exemplo

```
GET /api/recursos
```

**Resposta (200 OK)**
```json
{
    "recursos": [
        {"id": 1, "nome": "Recurso 1", "descricao": "Descrição do recurso 1"},
        {"id": 2, "nome": "Recurso 2", "descricao": "Descrição do recurso 2"},
        {"id": 3, "nome": "Recurso 3", "descricao": "Descrição do recurso 3"}
    ]
}
```

## Autenticação

### Registro de Usuário

```
POST /api/register
```

**Parâmetros**
```json
{
    "name": "Nome Completo",
    "email": "usuario@exemplo.com",
    "password": "senha123",
    "role": "common" // opcional, valores: "common" ou "admin"
}
```

**Resposta (201 Created)**
```json
{
    "user": {
        "id": 1,
        "name": "Nome Completo",
        "email": "usuario@exemplo.com",
        "role": "common"
    }
}
```

**Resposta (400 Bad Request)**
```json
{
    "error": "Email already in use"
}
```

### Login

```
POST /api/login
```

**Parâmetros**
```json
{
    "email": "usuario@exemplo.com",
    "password": "senha123"
}
```

**Resposta (200 OK)**
```json
{
    "message": "Logged in"
}
```

**Resposta (401 Unauthorized)**
```json
{
    "error": "Invalid credentials"
}
```

## Usuários - Requer Autenticação

### Listar Usuários

```
GET /api/users
```

**Resposta (200 OK)**
```json
{
    "users": [
        {
            "id": 1,
            "name": "Nome Completo",
            "email": "usuario@exemplo.com",
            "role": "common"
        }
    ]
}
```

**Resposta (401 Unauthorized)**
```json
{
    "error": "Unauthorized. Please login first."
}
```

### Obter Usuário por ID

```
GET /api/users/{id}
```

**Resposta (200 OK)**
```json
{
    "user": {
        "id": 1,
        "name": "Nome Completo",
        "email": "usuario@exemplo.com",
        "role": "common"
    }
}
```

**Resposta (401 Unauthorized)**
```json
{
    "error": "Unauthorized. Please login first."
}
```

**Resposta (404 Not Found)**
```json
{
    "error": "Usuário não encontrado"
}
```

### Atualizar Usuário

```
PUT /api/users/{id}
```

**Parâmetros**
```json
{
    "name": "Novo Nome",
    "email": "novo@exemplo.com",
    "password": "novaSenha", // opcional
    "role": "admin" // opcional
}
```

**Resposta (200 OK)**
```json
{
    "user": {
        "id": 1,
        "name": "Novo Nome",
        "email": "novo@exemplo.com",
        "role": "admin"
    }
}
```

**Resposta (401 Unauthorized)**
```json
{
    "error": "Unauthorized. Please login first."
}
```

**Resposta (404 Not Found)**
```json
{
    "error": "Usuário não encontrado"
}
```

**Resposta (500 Internal Server Error)**
```json
{
    "error": "Erro ao atualizar usuário"
}
```

### Excluir Usuário

```
DELETE /api/users/{id}
```

**Resposta (200 OK)**
```json
{
    "message": "Usuário deletado com sucesso"
}
```

**Resposta (401 Unauthorized)**
```json
{
    "error": "Unauthorized. Please login first."
}
```

**Resposta (404 Not Found)**
```json
{
    "error": "Usuário não encontrado"
}
```

**Resposta (500 Internal Server Error)**
```json
{
    "error": "Erro ao excluir usuário"
}
```

### Listar Anúncios de um Usuário

```
GET /api/users/{id}/adsenses
```

**Resposta (200 OK)**
```json
{
    "adsenses": [
        {
            "id": 1,
            "title": "Título do Anúncio",
            "description": "Descrição do anúncio",
            "price": 99.90,
            "user_id": 1
        }
    ]
}
```

**Resposta (401 Unauthorized)**
```json
{
    "error": "Unauthorized. Please login first."
}
```

**Resposta (404 Not Found)**
```json
{
    "error": "Usuário não encontrado"
}
```

## Anúncios (Adsenses) - Requer Autenticação

### Listar Anúncios

```
GET /api/adsenses
```

**Resposta (200 OK)**
```json
{
    "adsenses": [
        {
            "id": 1,
            "title": "Título do Anúncio",
            "description": "Descrição do anúncio",
            "price": 99.90,
            "user_id": 1
        }
    ]
}
```

**Resposta (401 Unauthorized)**
```json
{
    "error": "Unauthorized. Please login first."
}
```

### Obter Anúncio por ID

```
GET /api/adsenses/{id}
```

**Resposta (200 OK)**
```json
{
    "adsense": {
        "id": 1,
        "title": "Título do Anúncio",
        "description": "Descrição do anúncio",
        "price": 99.90,
        "user_id": 1
    }
}
```

**Resposta (401 Unauthorized)**
```json
{
    "error": "Unauthorized. Please login first."
}
```

**Resposta (404 Not Found)**
```json
{
    "error": "Not found"
}
```

### Criar Anúncio

```
POST /api/adsenses
```

**Parâmetros**
```json
{
    "title": "Título do Anúncio",
    "description": "Descrição do anúncio",
    "price": 99.90
}
```

**Resposta (201 Created)**
```json
{
    "adsense": {
        "id": 1,
        "title": "Título do Anúncio",
        "description": "Descrição do anúncio",
        "price": 99.90,
        "user_id": 1
    }
}
```

**Resposta (401 Unauthorized)**
```json
{
    "error": "Unauthorized"
}
```

**Resposta (500 Internal Server Error)**
```json
{
    "error": "Failed to save advertisement"
}
```

### Atualizar Anúncio

```
PUT /api/adsenses/{id}
```

**Parâmetros**
```json
{
    "title": "Novo Título",
    "description": "Nova descrição",
    "price": 149.90
}
```

**Resposta (200 OK)**
```json
{
    "adsense": {
        "id": 1,
        "title": "Novo Título",
        "description": "Nova descrição",
        "price": 149.90,
        "user_id": 1
    }
}
```

**Resposta (401 Unauthorized)**
```json
{
    "error": "Unauthorized"
}
```

**Resposta (403 Forbidden)**
```json
{
    "error": "Forbidden"
}
```

**Resposta (404 Not Found)**
```json
{
    "error": "Not found"
}
```

**Resposta (500 Internal Server Error)**
```json
{
    "error": "Failed to update advertisement"
}
```

### Excluir Anúncio

```
DELETE /api/adsenses/{id}
```

**Resposta (200 OK)**
```json
{
    "message": "Deleted"
}
```

**Resposta (401 Unauthorized)**
```json
{
    "error": "Unauthorized"
}
```

**Resposta (403 Forbidden)**
```json
{
    "error": "Forbidden"
}
```

**Resposta (404 Not Found)**
```json
{
    "error": "Not found"
}
```

**Resposta (500 Internal Server Error)**
```json
{
    "error": "Failed to delete advertisement"
}
```

## Imagens - Requer Autenticação

### Listar Imagens

```
GET /api/images
```

**Resposta (200 OK)**
```json
{
    "images": [
        {
            "id": 1,
            "url": "https://res.cloudinary.com/your-cloud-name/image/upload/v1234567890/ads/abcdef.jpg",
            "public_id": "ads/abcdef",
            "metadata": {
                "format": "jpg",
                "width": 800,
                "height": 600,
                "bytes": 123456
            },
            "adsense_id": 1
        }
    ]
}
```

**Resposta (401 Unauthorized)**
```json
{
    "error": "Unauthorized. Please login first."
}
```

### Obter Imagem por ID

```
GET /api/images/{id}
```

**Resposta (200 OK)**
```json
{
    "image": {
        "id": 1,
        "url": "https://res.cloudinary.com/your-cloud-name/image/upload/v1234567890/ads/abcdef.jpg",
        "public_id": "ads/abcdef",
        "metadata": {
            "format": "jpg",
            "width": 800,
            "height": 600,
            "bytes": 123456
        },
        "adsense_id": 1
    }
}
```

**Resposta (401 Unauthorized)**
```json
{
    "error": "Unauthorized. Please login first."
}
```

**Resposta (404 Not Found)**
```json
{
    "error": "Not found"
}
```

### Adicionar Imagem

```
POST /api/images
```

**Parâmetros (multipart/form-data)**
```
adsense_id: 1
image: [arquivo de imagem]
```

**Resposta (201 Created)**
```json
{
    "image": {
        "id": 1,
        "url": "https://res.cloudinary.com/your-cloud-name/image/upload/v1234567890/ads/abcdef.jpg",
        "public_id": "ads/abcdef",
        "metadata": {
            "format": "jpg",
            "width": 800,
            "height": 600,
            "bytes": 123456
        },
        "adsense_id": 1
    }
}
```

**Resposta (401 Unauthorized)**
```json
{
    "error": "Unauthorized"
}
```

**Resposta (500 Internal Server Error)**
```json
{
    "error": "Failed to upload image"
}
```

### Excluir Imagem

```
DELETE /api/images/{id}
```

**Resposta (200 OK)**
```json
{
    "message": "Image deleted successfully"
}
```

**Resposta (401 Unauthorized)**
```json
{
    "error": "Unauthorized"
}
```

**Resposta (404 Not Found)**
```json
{
    "error": "Not found"
}
```

**Resposta (500 Internal Server Error)**
```json
{
    "error": "Failed to delete image"
}
```

## Rotas Administrativas - Requer Autenticação como Admin

### Estatísticas

```
GET /api/admin/stats
```

**Resposta (200 OK)**
```json
{
    "total_users": 10,
    "total_adsenses": 25
}
```

**Resposta (401 Unauthorized)**
```json
{
    "error": "Unauthorized. Please login first."
}
```

**Resposta (403 Forbidden)**
```json
{
    "error": "Forbidden. Admin access required."
}
```

### Listar Todos os Usuários (Admin)

```
GET /api/admin/users
```

**Resposta (200 OK)**
```json
{
    "users": [
        {
            "id": 1,
            "name": "Nome Completo",
            "email": "usuario@exemplo.com",
            "role": "common"
        }
    ]
}
```

**Resposta (401 Unauthorized)**
```json
{
    "error": "Unauthorized. Please login first."
}
```

**Resposta (403 Forbidden)**
```json
{
    "error": "Forbidden. Admin access required."
}
```

### Listar Todos os Anúncios (Admin)

```
GET /api/admin/adsenses
```

**Resposta (200 OK)**
```json
{
    "adsenses": [
        {
            "id": 1,
            "title": "Título do Anúncio",
            "description": "Descrição do anúncio",
            "price": 99.90,
            "user_id": 1
        }
    ]
}
```

**Resposta (401 Unauthorized)**
```json
{
    "error": "Unauthorized. Please login first."
}
```

**Resposta (403 Forbidden)**
```json
{
    "error": "Forbidden. Admin access required."
}
``` 