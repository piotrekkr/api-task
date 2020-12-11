#!/usr/bin/env bash

set -e

# replace env variables inside template and write it as proper configuration
envsubst '${PHP_FPM_URI}' < /etc/nginx/conf.d/default.conf.template > /etc/nginx/conf.d/default.conf

# wait for services if provided
wait-for-it.sh "${PHP_FPM_URI}" -t 30;

exec "$@"
