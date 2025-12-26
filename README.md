# Portfolio API

API RESTful moderna e de alta performance para gerenciamento de portfólio, construída com [Hyperf Framework](https://hyperf.io) (PHP/Swoole).

## 📋 Sobre o Projeto

Esta é uma API completa para gerenciar um portfólio pessoal, incluindo projetos, blog posts, experiências profissionais, tecnologias e redes sociais. A aplicação utiliza autenticação via GitHub OAuth e JWT para rotas administrativas.

### Principais Funcionalidades

- ✅ **Gerenciamento de Projetos** - CRUD completo de projetos com traduções
- ✅ **Sistema de Blog** - Posts com suporte a múltiplos idiomas
- ✅ **Experiências Profissionais** - Histórico de experiências com tecnologias
- ✅ **Tecnologias** - Catálogo de skills e tecnologias
- ✅ **Redes Sociais** - Links para perfis sociais
- ✅ **Autenticação GitHub OAuth** - Login seguro via GitHub
- ✅ **Autorização JWT** - Proteção de rotas administrativas
- ✅ **Suporte a Traduções** - Conteúdo multilíngue (i18n)
- ✅ **Cache Redis** - Performance otimizada
- ✅ **API REST** - Endpoints bem estruturados e documentados

## 🏗️ Arquitetura e Tecnologias

### Stack Principal

- **Framework**: [Hyperf 3.1](https://hyperf.io) - Framework assíncrono de alta performance
- **Runtime**: [Swoole](https://www.swoole.co.uk/) - Extension PHP para programação assíncrona
- **PHP**: 8.3+
- **Banco de Dados**:
  - SQLite (desenvolvimento)
  - PostgreSQL (produção)
- **Cache**: Redis 7
- **Autenticação**: GitHub OAuth + JWT

### Estrutura do Projeto

```
portfolio-api/
├── app/
│   ├── Command/          # Comandos CLI customizados
│   ├── Constants/        # Constantes e códigos de erro
│   ├── Contracts/        # Interfaces e contratos
│   ├── Controller/       # Controllers HTTP
│   │   ├── Admin/        # Controllers administrativos (protegidos)
│   │   └── Portfolio/    # Controllers públicos
│   ├── Database/
│   │   └── Seeds/        # Seeders para popular o banco
│   ├── DTO/              # Data Transfer Objects
│   ├── Exception/        # Exceções customizadas
│   ├── Interface/        # Interfaces de repositórios
│   ├── Middleware/       # Middlewares HTTP
│   ├── Model/            # Models Eloquent
│   ├── Repository/       # Implementações de repositórios
│   ├── Request/          # Form Request Validations
│   ├── Resource/         # API Resources (transformers)
│   ├── Services/         # Lógica de negócio
│   └── Traits/           # Traits reutilizáveis
├── config/               # Arquivos de configuração
│   ├── autoload/         # Configurações carregadas automaticamente
│   └── routes.php        # Definição de rotas
├── docker/               # Arquivos Docker
│   └── scripts/          # Scripts de inicialização
├── migrations/           # Migrations do banco de dados
├── runtime/              # Arquivos temporários e cache
├── storage/              # Arquivos de armazenamento
├── docker-compose.yml    # Docker Compose (desenvolvimento)
├── docker-compose.prod.yml # Docker Compose (produção)
├── Dockerfile            # Multi-stage Dockerfile
└── Makefile              # Comandos facilitadores
```

## 🚀 Início Rápido

### Pré-requisitos

- [Docker](https://www.docker.com/get-started) e Docker Compose
- [Make](https://www.gnu.org/software/make/) (opcional, mas recomendado)

### Instalação

1. **Clone o repositório**

```bash
git clone <repository-url>
cd portfolio-api
```

2. **Configure as variáveis de ambiente**

```bash
cp .env.example .env
```

Edite o arquivo `.env` e configure:
- `JWT_SECRET_KEY` - Chave secreta para JWT (gere com: `php -r "echo base64_encode(random_bytes(32));"`)
- `ADMIN_ID` - Seu GitHub ID (obtenha em: https://api.github.com/users/seu-username)

3. **Inicie o ambiente de desenvolvimento**

```bash
make init
```

Ou manualmente:

```bash
docker-compose up -d
docker-compose exec app sh /opt/www/docker/scripts/init-dev.sh
```

4. **Acesse a aplicação**

A API estará disponível em: http://localhost:9501

## 📚 Comandos Disponíveis

O projeto inclui um `Makefile` com comandos facilitadores:

```bash
make help              # Lista todos os comandos disponíveis
make dev               # Inicia ambiente de desenvolvimento
make prod              # Inicia ambiente de produção
make build             # Builda as imagens Docker
make down              # Para todos os serviços
make restart           # Reinicia os serviços
make logs              # Visualiza logs da aplicação
make shell             # Acessa shell do container
make db-migrate        # Executa migrations
make db-seed           # Executa seeders
make db-reset          # Reset completo do banco (fresh + seed)
make composer-install  # Instala dependências
make test              # Executa testes
make cs-fix            # Corrige estilo de código
make analyse           # Análise estática (PHPStan)
```

## 🔌 Endpoints da API

### Rotas Públicas (Portfolio)

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| GET | `/portfolio/about` | Informações sobre mim |
| GET | `/portfolio/blog` | Lista de posts |
| GET | `/portfolio/blog/{id}` | Detalhes de um post |
| GET | `/portfolio/experiences` | Experiências profissionais |
| GET | `/portfolio/projects` | Lista de projetos |
| GET | `/portfolio/projects/{id}` | Detalhes de um projeto |
| GET | `/portfolio/social` | Links de redes sociais |
| GET | `/portfolio/techs` | Tecnologias/Skills |
| POST | `/portfolio/chat` | Endpoint de chat |

### Rotas de Autenticação

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| GET | `/auth/github/redirect` | Inicia OAuth GitHub |
| GET | `/auth/github/callback` | Callback OAuth GitHub |
| POST | `/auth/logout` | Logout (requer auth) |
| PUT | `/auth/me` | Atualiza dados do usuário (requer auth) |

### Rotas Administrativas (requerem autenticação JWT)

Todas as rotas abaixo requerem header: `Authorization: Bearer <token>`

**Blog**
- `GET /blog` - Lista posts
- `GET /blog/{id}` - Visualiza post
- `POST /blog/create` - Cria post
- `PUT /blog/{id}` - Atualiza post
- `DELETE /blog/{id}` - Deleta post

**Projetos**
- `GET /projects` - Lista projetos
- `GET /projects/{id}` - Visualiza projeto
- `POST /projects/create` - Cria projeto
- `PUT /projects/{id}` - Atualiza projeto
- `DELETE /projects/{id}` - Deleta projeto

**Experiências, Social, Techs e About** seguem o mesmo padrão CRUD.

## 🐳 Docker

### Desenvolvimento

Usa SQLite + Redis, com hot-reload ativado:

```bash
docker-compose up -d
```

### Produção

Usa PostgreSQL + Redis, otimizado para performance:

```bash
docker-compose -f docker-compose.prod.yml up -d
```

Configure as variáveis de ambiente de produção:

```bash
DB_DRIVER=pgsql
DB_HOST=postgres
DB_DATABASE=portfolio
DB_USERNAME=portfolio
DB_PASSWORD=sua-senha-segura
JWT_SECRET_KEY=sua-chave-jwt-segura
ADMIN_ID=seu-github-id
```

## 🧪 Testes

Execute os testes com:

```bash
make test
# ou
docker-compose exec app composer test
```

## 🔧 Desenvolvimento

### Code Style

```bash
make cs-fix
```

### Análise Estática

```bash
make analyse
```

### Hot Reload

O ambiente de desenvolvimento já vem com hot-reload configurado via `server:watch`:

```bash
make watch
```

## 📦 Database

### Migrations

```bash
make db-migrate         # Executa migrations
make db-migrate-fresh   # Dropa tudo e recria
```

### Seeders

```bash
make db-seed   # Popula banco com dados de exemplo
make db-reset  # Fresh + Seed
```

## 🔐 Autenticação

O sistema usa uma combinação de GitHub OAuth + JWT:

1. **Login via GitHub**: Usuário autentica via OAuth
2. **Validação de Admin**: Sistema verifica se o GitHub ID corresponde ao `ADMIN_ID`
3. **Geração de JWT**: Token JWT é gerado e retornado
4. **Acesso a rotas protegidas**: Token deve ser enviado no header `Authorization: Bearer <token>`

## 🌍 Internacionalização

O projeto suporta múltiplos idiomas para:
- Posts (tabela `posts_translations`)
- Projetos (tabela `projects_translations`)
- Experiências (tabela `experiences_translation`)

## 📝 Licença

[Apache-2.0](LICENSE)

## 🤝 Contribuindo

Contribuições são bem-vindas! Sinta-se à vontade para abrir issues e pull requests.

## 📞 Suporte

Para questões e suporte, abra uma [issue](../../issues) no repositório.

---

**Desenvolvido com** ❤️ **usando Hyperf Framework**
