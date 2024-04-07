PHP_VERSION ?= 8.3
USER = $$(id -u)

composer:
	docker run --init -it --rm -u ${USER} -v "$$(pwd):/app" -w /app \
		composer:latest \
		composer install --ignore-platform-req=ext-mysqli

composer-up:
	docker run --init -it --rm -u ${USER} -v "$$(pwd):/app" -w /app \
		composer:latest \
		composer update --ignore-platform-req=ext-mysqli

psalm:
	docker run --init -it --rm -v "$$(pwd):/app" -e XDG_CACHE_HOME=/tmp -w /app \
		ghcr.io/kuaukutsu/php:${PHP_VERSION}-cli \
		./vendor/bin/psalm --alter --issues=MissingParamType --dry-run

phpcs:
	docker run --init -it --rm -u ${USER} -v "$$(pwd):/app" -w /app \
		ghcr.io/kuaukutsu/php:${PHP_VERSION}-cli \
		./vendor/bin/phpcs

phpcbf:
	docker run --init -it --rm -u ${USER} -v "$$(pwd):/app" -w /app \
		ghcr.io/kuaukutsu/php:${PHP_VERSION}-cli \
		./vendor/bin/phpcbf

app-cli-build:
	USER=${USER} docker-compose -f ./docker-compose.yml build cli

app-db-build:
	USER=${USER} docker-compose -f ./docker-compose.yml build db

app-build: app-cli-build app-db-build
	USER=${USER} docker-compose -f ./docker-compose.yml build app

cli:
	docker-compose -f ./docker-compose.yml run --rm -u ${USER} -w /app \
		cli sh

app-up:
	USER=${USER} docker-compose -f ./docker-compose.yml up -d --remove-orphans

app-down:
	docker-compose -f ./docker-compose.yml down --remove-orphans

app-tests:
	docker-compose -f ./docker-compose.yml run --rm -u ${USER} -w /app/tests \
		-e XDEBUG_MODE=off \
		app php test.php
