FROM php:7.4.7-cli-buster

COPY --from=composer:1.10.4 /usr/bin/composer /usr/bin/composer

RUN apt-get update \
    && apt-get install -y --no-install-recommends libxslt1-dev libcurl4-openssl-dev libzip-dev libpq-dev unzip git \
    && docker-php-ext-install zip xsl pdo pdo_pgsql