# بدل كتابة أوامر docker طويلة:
# make up     بدل: docker compose up -d
# make shell  بدل: docker compose exec php sh

.PHONY: migrate seed fresh logs assets cache-clear setup

# Local migrate (no Docker)
migrate:
	php artisan migrate

# Local seeders
seed:
	php artisan db:seed

# Reset + migrate + seed
fresh:
	php artisan migrate:fresh --seed

# Tail logs locally (if file exists)
logs:
	@test -f storage/logs/laravel.log && tail -f storage/logs/laravel.log || echo "No local log file found"

# Build frontend assets
assets:
	npm run build

# Clear caches locally
cache-clear:
	php artisan cache:clear
	php artisan config:clear
	php artisan route:clear
	php artisan view:clear

# Local setup (composer + npm)
setup:
	cp .env.example .env
	composer install
	php artisan key:generate
	php artisan storage:link
	php artisan migrate --seed
	npm ci
	npm run build
	@echo "✅ Local setup complete"