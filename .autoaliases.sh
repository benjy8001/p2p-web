#!/usr/bin/env bash

DOCKER_COMPOSE_OPTION="-T"
DOCKER_OPTION=""

npm() {
   docker run $DOCKER_OPTION --rm -v `pwd`/:/project -w /project -u $(id -u):$(id -g) node:lts-alpine npm "$@"
}

phpqa() {
   docker run $DOCKER_OPTION --rm -v `pwd`/:/project -w /project jakzal/phpqa:php8.1-alpine "$@"
}

cap() {
   docker run $DOCKER_OPTION --rm -v `pwd`/:/project -w /project advertile/docker-capistrano cap "$@"
}

composer-unused() {
    phpqa composer-unused -vvv
}

phpunit () {
    phpqa phpdbg -qrr /tools/phpunit -c . --colors=never --exclude-group excluded,functionnal $@
}

artisan() {
   docker exec -ti PaieRH php artisan $@
}

composer () {
   docker run --rm -e COMPOSER_AUTH=$COMPOSER_AUTH -v `pwd`/:/project -w /project composer:2 --ignore-platform-req=ext-exif --ignore-platform-req=ext-pcntl --ignore-platform-req=ext-gd $@
}

php-cs-fixer() {
  phpqa php-cs-fixer fix --dry-run --using-cache=no --verbose --diff $@
}

php-cs-fixer-fix() {
  phpqa php-cs-fixer fix --using-cache=no --verbose $@
}
