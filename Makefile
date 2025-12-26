.PHONY: help dev prod up down build logs shell db-migrate db-seed clean test

help: ## Show this help message
	@echo '📚 Portfolio API - Available Commands:'
	@echo ''
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[36m%-20s\033[0m %s\n", $$1, $$2}'

dev: ## Start development environment
	@echo '🚀 Starting development environment...'
	docker-compose up -d
	@echo '✅ Development server running at http://localhost:9501'

prod: ## Start production environment
	@echo '🚀 Starting production environment...'
	docker-compose -f docker-compose.prod.yml up -d
	@echo '✅ Production server running at http://localhost:9501'

build: ## Build Docker images
	@echo '🔨 Building Docker images...'
	docker-compose build

build-prod: ## Build production Docker images
	@echo '🔨 Building production Docker images...'
	docker-compose -f docker-compose.prod.yml build

up: dev ## Alias for dev

down: ## Stop all services
	@echo '⏹️  Stopping services...'
	docker-compose down
	docker-compose -f docker-compose.prod.yml down 2>/dev/null || true

restart: ## Restart development environment
	@echo '🔄 Restarting services...'
	docker-compose restart

logs: ## Show application logs
	docker-compose logs -f app

logs-redis: ## Show Redis logs
	docker-compose logs -f redis

shell: ## Access application container shell
	docker-compose exec app sh

db-migrate: ## Run database migrations
	docker-compose exec app php bin/hyperf.php migrate

db-migrate-fresh: ## Fresh migrate (drop all tables and re-run)
	docker-compose exec app php bin/hyperf.php migrate:fresh

db-seed: ## Run database seeders
	docker-compose exec app php bin/hyperf.php db:seed

db-reset: ## Reset database (fresh migrate + seed)
	docker-compose exec app php bin/hyperf.php migrate:fresh
	docker-compose exec app php bin/hyperf.php db:seed

init: ## Initialize development environment (first time setup)
	@echo '🔧 Initializing project...'
	@if [ ! -f .env ]; then \
		echo '📝 Creating .env file...'; \
		cp .env.example .env; \
	fi
	docker-compose up -d
	@echo '⏳ Waiting for services to be ready...'
	sleep 5
	docker-compose exec app sh /opt/www/docker/scripts/init-dev.sh
	@echo '✅ Project initialized! Access: http://localhost:9501'

clean: ## Clean up containers, volumes and cache
	@echo '🧹 Cleaning up...'
	docker-compose down -v
	docker-compose -f docker-compose.prod.yml down -v 2>/dev/null || true
	rm -rf runtime/container runtime/cache

composer-install: ## Install composer dependencies
	docker-compose exec app composer install

composer-update: ## Update composer dependencies
	docker-compose exec app composer update

test: ## Run tests
	docker-compose exec app composer test

cs-fix: ## Run code style fixer
	docker-compose exec app composer cs-fix

analyse: ## Run static analysis
	docker-compose exec app composer analyse

watch: ## Start development server with file watcher
	docker-compose exec app php bin/hyperf.php server:watch
