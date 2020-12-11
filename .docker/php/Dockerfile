# syntax = docker/dockerfile:1.0-experimental

####################
#### BASE BUILD ####
####################

FROM php:7.4.3-fpm-buster as base

WORKDIR /var/www

# set home env vars for user and for composer
ENV COMPOSER_HOME="/var/.composer"

# copy entrypoint script
COPY .docker/php/entrypoint.sh .docker/wait-for-it.sh /usr/local/bin/

# copy composer executable
COPY --from=composer:1.10.17 /usr/bin/composer /usr/bin/composer

RUN set -e; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
        libxslt1-dev=1.1.32-2.2~deb10u1 \
        libcurl4-openssl-dev=7.64.0-4+deb10u1 \
        libzip-dev=1.5.1-4 \
        libpq-dev=11.9-0+deb10u1 \
        #git=1:2.20.1-2+deb10u3 \
        unzip=6.0-23+deb10u1 ; \
    apt-get clean ; \
    rm -rf /var/lib/apt/lists/* ;\
    docker-php-ext-install zip xsl pdo pdo_pgsql ; \
    # cleanup application directory
    rm -rf /var/www/*; \
    # make scripts executable
    chmod +x /usr/local/bin/entrypoint.sh /usr/local/bin/wait-for-it.sh; \
    # remove default php-fpm configuration
    rm -f /usr/local/etc/php-fpm.d/*; \
    # create directories for PHP-FPM logs and composer
    mkdir -p "${COMPOSER_HOME}" /var/log/fpm;

# copy php-fpm configuration (default configuration files were removed earlier)
COPY .docker/php/php-fpm.conf /usr/local/etc/php-fpm.d/php-fpm.conf

# set entrypoint script
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

CMD [ "php-fpm" ]

####################
#### PROD BUILD ####
####################
FROM base as prod

ENV APP_ENV=prod
ENV APP_DEBUG=0

# copy prod php config
COPY .docker/php/php-prod.ini /usr/local/etc/php/php.ini

# copy application files
COPY composer.json composer.lock ./

# install composer dependencies first so it will be cached if composer.json or composer.lock didn't change
RUN composer install --prefer-dist --no-dev --no-suggest --no-cache --optimize-autoloader --no-scripts --no-progress

# copy application files
COPY . .

RUN set -e; \
    # remove unnecesary files in var/ directory
    rm -rf var/*/*; \
    # rebuild cache
    php bin/console cache:clear; \
    # www-data is default running user so it needs access to logs
    chown -R www-data:www-data var/log var/cache

# set APP_VERSION from build arg as env
ARG APP_VERSION=prod
ENV APP_VERSION="${APP_VERSION}"

####################
## QA BUILD ########
####################
FROM prod as qa

# install composer dev dependencies QA checks
RUN composer install --prefer-dist --no-suggest --no-cache --optimize-autoloader --no-scripts --no-progress

# run qa chacks
RUN composer qa:run

####################
#### DEV BUILD #####
####################
FROM base AS dev

# set version to dev
ENV APP_VERSION="dev"

# set home directory for app user
ENV HOME=/home/app

# copy dev php config
COPY .docker/php/php-dev.ini /usr/local/etc/php/php.ini

# get running user from build args
ARG CONTAINER_UID
ARG CONTAINER_GID

# add running user account and install xdebug
RUN set -e; \
    # add group app
    groupadd --gid ${CONTAINER_GID} app ;\
    # add app user required for logrotate (it expects log owner to be in passwd file)
    useradd app \
        # This is important! See https://github.com/moby/moby/issues/5419#issuecomment-41478290
        --no-log-init \
        --home "${HOME}" \
        --create-home \
        --uid "${CONTAINER_UID}" \
        --gid "${CONTAINER_GID}" \
        --shell /bin/bash ; \
    # install xdebug
    pecl install xdebug; \
    docker-php-ext-enable xdebug; \
    # cleanup
    rm -rf /tmp/pear; \
    chown -R app:app \
      "${HOME}" \
      "${COMPOSER_HOME}" \
      /var/www/ \
      /usr/local/var/log/ ;

# run this conatiner as user app (with UID and GID from build arg),
# file permissions are working well since container running user has same UID/GID as developer running it
USER app:app
