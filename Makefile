#!/usr/bin/env bash

ifndef VERBOSE
.SILENT:
endif

DOCKER_COMPOSE  = docker-compose
DOCKER          = docker
EXEC_PHP        = $(DOCKER_COMPOSE) exec -e COMPOSER_AUTH="${COMPOSER_AUTH}" phpfpm
ARTISAN         = $(EXEC_PHP) php artisan
CURRENT_DIR		= $(shell pwd)
LOCAL_ARTEFACTS = var/artefacts

IMAGE_AUDIT 	= jakzal/phpqa:1.83.1-php8.1-alpine
QA 				= docker run --rm -e COMPOSER_AUTH="${COMPOSER_AUTH}" -v $(CURRENT_DIR)/:/project -w /project $(IMAGE_AUDIT)
COMPOSER_BIN	= $(DOCKER) run --rm -e COMPOSER_AUTH="${COMPOSER_AUTH}" -v $(CURRENT_DIR)/:/project -w /project composer:2
COMPOSER 		= $(COMPOSER_BIN)

ifndef QUIET
#normal mode display text
QUIET_PARAM :=
define display
	@{ \
    printf $1; \
    }
endef
else
#  quiet enabled do nothing
QUIET_PARAM :=  -q
define display
endef
endif

#
# Presentation commands
# -------------------
#
coffee:
	printf "\033[32m You can go take a coffee while we work for you \033[0m\n"

banner:
	printf "\033[95m								\n"


#
.DEFAULT_GOAL := help
help: banner ## This help
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/  \x1b\[32m##/\x1b\[33m/'
.PHONY: help coffee banner

##
## Project
## -------
build: .env ## Build docker
	printf "\033[32m Deploy to Server \033[0m\n"
	$(DOCKER_COMPOSE) build

run: build ## Run project
	printf " 🏃\033[33m Running application ... \033[0m\n"
	$(DOCKER_COMPOSE) pull
	$(DOCKER_COMPOSE) up -d
	# wait 10s for containers being fully started
	sleep 10s
	printf "\n\n"

stop: ## Stop the VMs
	printf " \033[31m◉\033[0m\033[33m  Stopping application ... \033[0m\n"
	$(DOCKER_COMPOSE) stop
	printf "\n\n"

down: ## Stop the VMs
	printf " \033[31m◉\033[0m\033[33m  Stopping application ... \033[0m\n"
	$(DOCKER_COMPOSE) down --volumes
	printf "\n\n"

clean: ## Stop the VMs and cleaning
	@echo -n "Are you sure? [y/N] " && read ans && [ $${ans:-N} = y ]
	$(DOCKER_COMPOSE) down -v
	rm -rf vendor node_modules public/css public/vendor public/js public/storage storage/logs/laravel.log storage/debugbar/*.json

tests: ## Tests
	$(ARTISAN) test

connect: banner ## Connect to apache docker
	printf " 👷\033[33m  Application shell ... \033[0m\n"
	$(EXEC_PHP) bash
	printf "\033[33mBye \033[0m\n\n"

tinker: banner ## Run laravel tinker to apache docker
	printf " 👷\033[33m  Application shell ... \033[0m\n"
	$(ARTISAN) tinker
	printf "\033[33mBye \033[0m\n\n"

.env:
	cp .env.example .env

key: ## Generate APP_KEY in .env file
	$(ARTISAN) key:generate

install: start

start: .env run vendor assets permission init-database seed ## Run the project

##
## Config Caching
## -----------------
config-cache: ## Boosting performance
	$(ARTISAN) config:cache

config-clear: ## Clear the config cache
	$(ARTISAN) config:clear

##
## Route Caching
## -----------------
route-cache: ## Boosting performance
	$(ARTISAN) route:cache

route-clear: ## Clear the route cache
	$(ARTISAN) route:clear

##
## Optimization
## -----------------
classmap-optimize: ## Classmap Optimization
	$(ARTISAN) optimize --force

composer-optimize: ## Composer Optimization
	$(COMPOSER) dumpautoload -o

##
## Vendor and assets
## -----------------
permission: ## Fix permission dir
	$(EXEC_PHP) chmod 777 storage -R

composer.lock: ## Run composer update
	$(COMPOSER) update --lock --no-interaction

vendor: ## Run composer install (normally, wee only need to run composer.lock)
	printf " 💽\033[33m Start Composer ... \033[0m\n"
	$(COMPOSER) install ${QUIET_PARAM}

composer-install: ## Run composer install Dev
	$(COMPOSER) install

composer-install-prod: ## Run composer install Prod
	$(COMPOSER) install --no-dev --optimize-autoloader

node-modules:
	$(DOCKER) run  --rm -v `pwd`/:/project -w /project -u $(shell id -u):$(shell id -g) node:lts-alpine npm install

assets: node-modules ## Compile assets in dev mode
	$(DOCKER) run  --rm -v `pwd`/:/project -w /project -u $(shell id -u):$(shell id -g) node:lts-alpine npm run dev

assets-prod: node-modules ## Compile assets in prod mode
	$(DOCKER) run  --rm -v `pwd`/:/project -w /project -u $(shell id -u):$(shell id -g) node:lts-alpine npm run prod

.PHONY: assets vendor composer.lock

##
## Database
## -----------------
init-database: ## Delete and create database
	$(call display," 💽\033[33m Initialize database ... \033[0m\n")
	$(ARTISAN) migrate:fresh

migration: ## Generate a new eloquent migration
ifdef COMMAND_ARGS
	$(ARTISAN) make:migration $(COMMAND_ARGS)
else
	$(ARTISAN) make:migration new_migration_file
endif

migrate: ## Play latest eloquent migrations
	$(ARTISAN) migrate $(QUIET_PARAM)

seed: ## Populate DB
	$(ARTISAN) db:seed

##
## Other init
## -----------------
init-storage: ## Symlink storage dir
	$(ARTISAN) storage:link

##
## Tools
## -----------------
lint: lp ly

pull_qa: ## Pull lastest update of docker image audit
	$(DOCKER) pull $(IMAGE_AUDIT)

ly: ## lint yaml files
	$(QA) sh -c  "find -iname '*.yml' -not -path './vendor/*' -not -path './node_modules/*' -print0 | xargs -0 -n1  yaml-lint;"

lp: ## lint php files
	$(QA) parallel-lint --blame --exclude vendor .

security: ## Check security of your dependencies (https://github.com/fabpot/local-php-security-checker)
	$(QA) local-php-security-checker

psalm: ## Finds errors in PHP applications
	$(QA) psalm --find-dead-code

phpmd: ## PHP Mess Detector (https://phpmd.org)
	$(QA) phpmd app text .phpmd.xml

phpcpd: ## PHP Copy/Paste Detector (https://github.com/sebastianbergmann/phpcpd)
	$(QA) phpcpd app

phpmetrics: artefacts  ## PhpMetrics (http://www.phpmetrics.org)
	$(QA) phpmetrics --report-html=$(LOCAL_ARTEFACTS)/phpmetrics app

pdepend: artefacts ## PDepend (https://pdepend.org/)
	$(QA) pdepend --summary-xml=$(LOCAL_ARTEFACTS)/summary.xml --jdepend-chart=$(LOCAL_ARTEFACTS)/jdepend.svg --overview-pyramid=$(LOCAL_ARTEFACTS)/pyramid.svg app

php-documentor: ## PHP Documentor
	$(QA) phpDocumentor -d app -t phpDocumentor

php-cs-fixer: ## php-cs-fixer (http://cs.sensiolabs.org)
	$(QA) php-cs-fixer fix --dry-run --using-cache=no --verbose --diff

apply-php-cs-fixer-all: ## apply php-cs-fixer fixes on all projet files
	$(QA) php-cs-fixer fix -q --using-cache=no

apply-php-cs-fixer: ## apply php-cs-fixer fixes on new files
	$(eval NEW_FILES := $(shell git diff $(REF_BRANCH) --name-only| egrep '.php$$'))
	$(QA) sh -c 'for filename in $(NEW_FILES);  do if [ -f $$filename ]; then  echo  "fixing $$filename";  php-cs-fixer fix -q --using-cache=no $$filename; fi; done;'

rector: ## Tool for instant code upgrades and refactoring
	$(QA) rector process app --dry-run

larastan: ## PHP Static Analysis Tool with Laravel presets (https://github.com/nunomaduro/larastan)
	$(QA) phpstan-larastan analyse -l 1 --memory-limit=-1 app # -c .phpstan.neon

deprecation-detector: artefacts ## Run deprecation detector
	$(QA) deprecation-detector check ./app --log-html $(LOCAL_ARTEFACTS)/deprecation/index.html

phpunit: artefacts ## Run phpunit
	printf " 💽\033[33m Start PHPUnit ... \033[0m\n"
ifdef COMMAND_ARGS
	$(QA) phpdbg -qrr -d memory_limit=-1 /tools/phpunit $(COMMAND_ARGS) --report-useless-tests --no-coverage --colors=never
else
	$(QA) phpdbg -qrr -d memory_limit=-1 /tools/phpunit --colors=never --testsuite "Unit Tests" --log-junit $(LOCAL_ARTEFACTS)/unitreport.xml --coverage-php $(LOCAL_ARTEFACTS)/coverage.cov --coverage-html=$(LOCAL_ARTEFACTS)/coverage --coverage-clover=$(LOCAL_ARTEFACTS)/coverage.xml --coverage-text
endif

infection: artefacts ## Run infection
	printf " 💽\033[33m Start Infection ... \033[0m\n"
	$(QA) phpdbg -qrr -d memory_limit=-1 /tools/infection --only-covered -s

artefacts:
	mkdir -p $(LOCAL_ARTEFACTS)

.PHONY: artefacts
