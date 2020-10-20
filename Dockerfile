FROM php:7.4.7-cli-buster

COPY --from=composer:1.10.4 /usr/bin/composer /usr/bin/composer

RUN apt-get update \
    && apt-get install -y --no-install-recommends libxslt1-dev libcurl4-openssl-dev libzip-dev libpq-dev unzip git curl\
    && docker-php-ext-install zip xsl pdo pdo_pgsql \
    && curl -L https://cs.symfony.com/download/php-cs-fixer-v2.phar -o /usr/local/bin/php-cs-fixer \
    && chmod a+x /usr/local/bin/php-cs-fixer