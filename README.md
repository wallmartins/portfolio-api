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
- ✅ **Documentação Swagger** - Interface interativa para testar endpoints
- ✅ **Upload de Imagens** - Integração com Cloudinary
- ✅ **CORS Configurado** - Suporte para requisições cross-origin

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

- **API**: http://localhost:9501
- **Swagger UI**: http://localhost:9500/swagger
- **Swagger JSON**: http://localhost:9500/http.json

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

## 📖 Documentação Swagger

A API possui documentação interativa via Swagger UI:

- **Swagger UI**: http://localhost:9500/swagger
- **Swagger JSON**: http://localhost:9500/http.json

### Características

- 📝 Documentação completa de todos os endpoints
- 🧪 Teste endpoints diretamente pela interface
- 🔐 Suporte para autenticação Bearer (JWT)
- 🌍 Especificação OpenAPI 3.0

### Autenticação no Swagger

Para testar endpoints protegidos:
1. Faça login via `/auth/github/callback` para obter o token JWT
2. Clique no botão "Authorize" no Swagger UI
3. Cole o token no campo de autenticação (Bearer token)
4. Agora você pode testar endpoints administrativos

## 🐳 Docker

O projeto utiliza Docker multi-stage com suporte para desenvolvimento e produção.

### Desenvolvimento (SQLite)

Ideal para desenvolvimento local com hot-reload:

```bash
# Iniciar
docker-compose up -d

# Ver logs
docker-compose logs -f app

# Acessar shell
docker-compose exec app sh
```

**Características**:
- ✅ Database: SQLite (arquivo local)
- ✅ Hot-reload ativado (`server:watch`)
- ✅ Code mounted como volume (mudanças refletem instantaneamente)
- ✅ Redis para cache
- ✅ Porta API: 9501
- ✅ Porta Swagger: 9500

### Produção (PostgreSQL)

Otimizado para ambiente de produção:

```bash
# Copiar arquivo de configuração
cp .env.prod.example .env

# Configurar variáveis de produção (obrigatório!)
# - JWT_SECRET_KEY: gere com `php -r "echo base64_encode(random_bytes(32));"`
# - DB_PASSWORD: senha forte para PostgreSQL
# - ADMIN_ID: seu GitHub ID
# - CLOUDINARY_*: credenciais do Cloudinary

# Iniciar
docker-compose -f docker-compose.prod.yml up -d

# Ver status
docker-compose -f docker-compose.prod.yml ps

# Ver logs
docker-compose -f docker-compose.prod.yml logs -f app
```

**Características**:
- ✅ Database: PostgreSQL 16
- ✅ Build otimizado (classmap authoritative)
- ✅ Sem dependências de desenvolvimento
- ✅ Redis com persistência
- ✅ Health checks configurados
- ✅ Auto-restart em caso de falha

### Migração de Ambientes

A aplicação suporta tanto SQLite quanto PostgreSQL sem mudanças no código!

**SQLite → PostgreSQL:**
```bash
# 1. Altere no .env
DB_DRIVER=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=portfolio
DB_USERNAME=portfolio
DB_PASSWORD=sua-senha

# 2. Reinicie o container
docker-compose restart app

# 3. As migrations rodarão automaticamente
```

**PostgreSQL → SQLite:**
```bash
# 1. Altere no .env
DB_DRIVER=sqlite
DB_DATABASE=/opt/www/runtime/database.sqlite

# 2. Reinicie o container
docker-compose restart app
```

### Comandos Docker Úteis

```bash
# Parar todos os serviços
docker-compose down

# Parar e remover volumes (limpa dados)
docker-compose down -v

# Rebuild completo
docker-compose build --no-cache
docker-compose up -d

# Executar migrations manualmente
docker-compose exec app php bin/hyperf.php migrate

# Gerar documentação Swagger
docker-compose exec app php bin/hyperf.php gen:swagger

# Backup PostgreSQL (produção)
docker-compose -f docker-compose.prod.yml exec postgres \
  pg_dump -U portfolio portfolio > backup.sql

# Restaurar backup
docker-compose -f docker-compose.prod.yml exec -T postgres \
  psql -U portfolio portfolio < backup.sql
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

## ⚙️ Configurações Importantes

### Variáveis de Ambiente Obrigatórias

```env
# Segurança (CRÍTICO!)
JWT_SECRET_KEY=gere-uma-chave-segura-aqui
ADMIN_ID=seu-github-user-id

# Database (muda por ambiente)
DB_DRIVER=sqlite|pgsql

# Cloudinary (para upload de imagens)
CLOUDINARY_CLOUD_NAME=seu-cloud-name
CLOUDINARY_API_KEY=sua-api-key
CLOUDINARY_API_SECRET=seu-api-secret
```

### Portas Utilizadas

- `9501` - API Principal (HTTP Server)
- `9500` - Swagger UI
- `6379` - Redis
- `5432` - PostgreSQL (somente produção)

### Requisitos de Sistema

**Desenvolvimento:**
- Docker 20.10+
- Docker Compose 2.0+
- 2GB RAM mínimo

**Produção:**
- Docker 20.10+
- Docker Compose 2.0+
- 4GB RAM recomendado
- PostgreSQL 16+
- Redis 7+

## 🔧 Troubleshooting

### Porta já em uso

```bash
# Encontrar processo usando a porta
lsof -i :9501

# Matar processo (se necessário)
kill -9 <PID>

# Ou alterar porta no docker-compose.yml
ports:
  - "9502:9501"  # Nova porta externa
```

### Erro ao conectar com banco de dados

**SQLite:**
```bash
# Verificar permissões
docker-compose exec app ls -la /opt/www/runtime/

# Recriar database
docker-compose exec app rm /opt/www/runtime/database.sqlite
docker-compose restart app
```

**PostgreSQL:**
```bash
# Verificar se PostgreSQL está rodando
docker-compose -f docker-compose.prod.yml ps postgres

# Ver logs do PostgreSQL
docker-compose -f docker-compose.prod.yml logs postgres

# Testar conexão
docker-compose -f docker-compose.prod.yml exec app \
  pg_isready -h postgres -p 5432 -U portfolio
```

### Migrations falhando

```bash
# Rodar migrations manualmente
docker-compose exec app php bin/hyperf.php migrate --force

# Ver status das migrations
docker-compose exec app php bin/hyperf.php migrate:status

# Rollback última migration
docker-compose exec app php bin/hyperf.php migrate:rollback

# Reset completo (cuidado!)
docker-compose exec app php bin/hyperf.php migrate:fresh
```

### Problemas com Redis

```bash
# Verificar se Redis está respondendo
docker-compose exec redis redis-cli ping
# Deve retornar: PONG

# Limpar cache do Redis
docker-compose exec redis redis-cli FLUSHALL

# Reiniciar Redis
docker-compose restart redis
```

### Swagger não carrega

```bash
# Regerar documentação Swagger
docker-compose exec app php bin/hyperf.php gen:swagger

# Verificar se arquivo foi gerado
docker-compose exec app ls -la /opt/www/storage/swagger/

# Reiniciar aplicação
docker-compose restart app
```

### Container não inicia

```bash
# Ver logs completos
docker-compose logs app

# Rebuild sem cache
docker-compose down
docker-compose build --no-cache
docker-compose up -d

# Verificar variáveis de ambiente
docker-compose exec app printenv | grep DB_
```

### Limpeza Completa

```bash
# Parar tudo e remover volumes
docker-compose down -v

# Remover imagens antigas
docker system prune -a -f

# Reconstruir do zero
docker-compose build --no-cache
docker-compose up -d
```

## 📝 Licença

[Apache-2.0](LICENSE)

## 🤝 Contribuindo

Contribuições são bem-vindas! Sinta-se à vontade para abrir issues e pull requests.

### Como Contribuir

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/MinhaFeature`)
3. Commit suas mudanças (`git commit -m 'Adiciona MinhaFeature'`)
4. Push para a branch (`git push origin feature/MinhaFeature`)
5. Abra um Pull Request

## 📞 Suporte

Para questões e suporte:
- 🐛 Bugs e Issues: [GitHub Issues](../../issues)
- 📖 Documentação: [Hyperf Documentation](https://hyperf.wiki)
- 💬 Discussões: [GitHub Discussions](../../discussions)

---

**Desenvolvido com** ❤️ **usando Hyperf Framework**
