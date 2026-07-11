# بدل كتابة أوامر docker طويلة:
# make up     بدل: docker compose up -d
# make shell  بدل: docker compose exec php sh

.PHONY: up down shell migrate seed fresh logs

# تشغيل كل الـ containers
up:
	docker compose up -d --build

# إيقاف كل الـ containers
down:
	docker compose down

# دخول shell داخل container الـ PHP
shell:
	docker compose exec php sh

# تشغيل migrations
migrate:
	docker compose exec php php artisan migrate

# تشغيل seeders
seed:
	docker compose exec php php artisan db:seed

# Reset كامل + migrate + seed
fresh:
	docker compose exec php php artisan migrate:fresh --seed

# متابعة الـ logs
logs:
	docker compose logs -f

# بناء assets
assets:
	docker compose exec php npm run build

# تنظيف الكاش
cache-clear:
	docker compose exec php php artisan cache:clear
	docker compose exec php php artisan config:clear
	docker compose exec php php artisan route:clear
	docker compose exec php php artisan view:clear

# تهيئة المشروع من الصفر
setup:
	cp .env.docker .env
	docker compose up -d --build
	docker compose exec php composer install
	docker compose exec php php artisan key:generate
	docker compose exec php php artisan storage:link
	docker compose exec php php artisan migrate --seed
	docker compose exec php npm install
	docker compose exec php npm run build
	@echo "✅ Valex is ready at https://localhost"