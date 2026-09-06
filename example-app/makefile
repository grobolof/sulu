include .env

# Запустить контейнеры:
up:
	docker compose up -d --build --remove-orphans

# Остановить контейнеры:
stop:
	docker compose stop

# Остановить и удалить контейнеры:
down:
	docker compose down -v

# Очистить кэш Sulu (admin и website).
# error_log=/dev/null глушит User Deprecated от Doctrine/Flysystem/Twig при прогреве контейнера.
cache-clear:
	docker compose exec -T application rm -rf var/cache
	docker compose exec -it application php -d error_log=/dev/null bin/adminconsole cache:clear
	docker compose exec -it application php -d error_log=/dev/null bin/websiteconsole cache:clear

# КОМАНДЫ ДОСТУПНЫ ПРИ УСЛОВИИ ЧТО ПРОЕКТ SULU УЖЕ СОЗДАН:

## Инициализировать Sulu (схема, фикстуры, пользователь admin/admin):
sulu-build:
	docker compose exec -it application php bin/adminconsole sulu:build dev

## Создать файл миграции:
migrations-create:
	docker compose exec -it application php bin/adminconsole doctrine:migrations:diff --no-interaction --namespace=DoctrineMigrations

## Накатить все миграции:
migrations-up:
	docker compose exec -it application php bin/adminconsole doctrine:migrations:migrate --no-interaction

## Откатить последнюю пачку миграций:
migrations-down:
	docker compose exec -it application php bin/adminconsole doctrine:migrations:migrate prev --no-interaction

## Произвольная команда adminconsole:
console: ## Пример: make console CMD="debug:router"
	docker compose exec -it application php bin/adminconsole $(CMD)

## Произвольная команда websiteconsole:
websiteconsole: ## Пример: make websiteconsole CMD="debug:router"
	docker compose exec -it application php bin/websiteconsole $(CMD)

# КОМАНДА ДОСТУПНА ПРИ УСЛОВИИ ЧТО УСТАНОВЛЕН ПАКЕТ PHPSTAN:

## Проверить код на ошибки:
# phpstan-symfony читает дамп контейнера website/dev (APP_ENV в .env = prod).
phpstan:
	docker compose exec -it application php -d error_log=/dev/null bin/websiteconsole cache:warmup --env=dev
	docker compose exec -it application php vendor/bin/phpstan analyse

# КОМАНДЫ ДОСТУПНЫ ПРИ УСЛОВИИ ЧТО УСТАНОВЛЕН ПАКЕТ PHPCS:

## Исправить ошибки:
phpcs:
	docker compose exec -it application php vendor/bin/php-cs-fixer fix --allow-risky=yes

## Проверить код на ошибки без исправления:
phpcs-dry:
	docker compose exec -it application php vendor/bin/php-cs-fixer fix --allow-risky=yes --dry-run || true

# КОМАНДЫ ДОСТУПНЫ ПРИ УСЛОВИИ ЧТО УСТАНОВЛЕН ПАКЕТ RECTOR:

## Исправить ошибки:
rector:
	docker compose exec -it application php vendor/bin/rector process

## Проверить код на ошибки без исправления:
rector-dry:
	docker compose exec -it application php vendor/bin/rector process --dry-run || true

# КОМАНДА ДОСТУПНА ПРИ УСЛОВИИ ЧТО ЕСТЬ ТЕСТЫ PHPUNIT:

## Запустить тесты:
test:
	docker compose exec -it application php bin/phpunit
