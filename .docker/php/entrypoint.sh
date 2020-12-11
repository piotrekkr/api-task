#!/usr/bin/env bash

set -e

# wait for services if provided
# should be list of hosts urls separated by space
if [[ -n "${WAIT_FOR_SERVICES}" ]]; then
    for svc in ${WAIT_FOR_SERVICES}; do
        # extract host and port
        svc_host=$( echo "$svc" | php -r 'echo parse_url(trim(fgets(STDIN)), PHP_URL_HOST);' )
        svc_port=$( echo "$svc" | php -r 'echo parse_url(trim(fgets(STDIN)), PHP_URL_PORT);' )
        wait-for-it.sh "$svc_host:$svc_port" -t 30;
    done
fi

exec "$@"
