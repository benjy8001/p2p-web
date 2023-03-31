ARG PHP_VERSION="8.1.9"
ARG ALPINE_VERSION="3.15"
# Arguments defined in docker-compose.yml
ARG user
ARG uid

FROM php:${PHP_VERSION}-fpm-alpine${ALPINE_VERSION}

# Install system dependencies
RUN apk update && apk add --no-cache \
        bash \
        curl \
        libpng-dev \
        libxml2-dev \
        libzip-dev \
        gnupg \
        libpq-dev \
        libjpeg-turbo-dev \
        freetype-dev \
        oniguruma-dev \
        icu-dev \
        supervisor && \
    apk add --no-cache --virtual .tools \
        git \
        unzip && \
    docker-php-ext-install mbstring exif pcntl bcmath pdo_mysql zip && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd && \
    docker-php-ext-configure intl && \
    docker-php-ext-install intl && \
    apk add gosu --update --no-cache --repository http://dl-3.alpinelinux.org/alpine/edge/testing/ --allow-untrusted && \
    addgroup bar && \
    adduser -D -h /home -s /bin/sh -G bar foo

COPY entrypoint.sh /entrypoint
COPY php/php.ini /usr/local/etc/php/conf.d/custom.ini
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN chmod +x /entrypoint

# Set working directory
WORKDIR /var/www/html

ENTRYPOINT ["/entrypoint"]
