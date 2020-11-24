#!/usr/bin/env bash

set -e

if [[ $WAIT_FOR_SERVICES == "1" ]]; then
    # extract database host and port from DATABASE_URL
    db_host_and_port=$( echo -n "$DATABASE_URL" | sed -r 's|.*@([^:]+:[0-9]+).*|\1|' )
    # wait for ports to be available
    wait-for-it.sh "${db_host_and_port}" -t 10;
fi

exec "$@"
