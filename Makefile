include .env

RUN_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))

sh:
	@docker-compose exec app sh

ps:
	@docker-compose ps

restart: down upd

up:
	@docker-compose up

upd:
	@docker-compose up -d

down:
	@docker-compose down

build:
	@docker-compose pull
	@docker-compose up -d --build
	@docker-compose exec app composer install

comp\:da:
	@docker-compose exec app composer dump-autoload

comp\:validate:
	@docker-compose exec app composer validate --no-check-all --strict

comp\:install:
	@docker-compose exec app composer install $(RUN_ARGS)

comp\:update:
	@docker-compose exec app composer update $(RUN_ARGS)

comp\:req:
	@docker-compose exec app composer require $(RUN_ARGS)

comp\:req-dev:
	@docker-compose exec app composer require --dev $(RUN_ARGS)

comp\:rem:
	@docker-compose exec app composer remove $(RUN_ARGS)

ch\:install:
	@docker-compose exec app ./vendor/bin/captainhook install --run-mode=docker --run-exec="docker-compose exec -T app"

test\:run:
	@docker-compose exec app ./vendor/bin/phpunit --testdox --colors=never --coverage-xml .artifacts/.phpunit/phpunit-coverage-report.xml --coverage-html .artifacts/.phpunit/phpunit-coverage-report.html --coverage-text --log-junit .artifacts/.phpunit/report.xml --disallow-test-output

test\:cov:
	@docker-compose exec app php check-code-coverage.php .artifacts/.phpunit/phpunit-coverage-report.xml/index.xml 100

synt\:fix:
	@docker-compose exec app ./vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.php --using-cache=no -v --allow-risky=yes

synt\:check:
	@docker-compose exec app ./vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.php --dry-run --allow-risky=yes --using-cache=no -v

php-md:
	@docker-compose exec app ./vendor/bin/phpmd src,tests text phpmd.ruleset.xml > .artifacts/phpmd-report.json

php-stan:
	@docker-compose exec app ./vendor/bin/phpstan analyse --memory-limit=512M --no-progress --error-format gitlab > .artifacts/phpstan-report.json

check: synt\:check php-md php-stan test\:run test\:cov

