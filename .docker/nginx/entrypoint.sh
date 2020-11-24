#!/usr/bin/env bash

set -e

# replace env variables inside template and write it as proper configuration
envsubst '${PHP_FPM_URI}' < /etc/nginx/conf.d/default.conf.template > /etc/nginx/conf.d/default.conf

# wait for php-fpm
#wait-for-it.sh "${PHP_FPM_URI}" -t 10;

exec "$@"
