# syntax = docker/dockerfile:1.0-experimental

FROM nginx:1.17.9 as base

WORKDIR /var/www

# fastcgi uri used to pass requestd to fastcgi backen
ENV PHP_FPM_URI="php:9000"

# nginx configuration
COPY .docker/nginx/nginx.conf /etc/nginx/nginx.conf

# copy nginx site configuration template,
# it will be used in entrypoint script to generate proper configuration with env variables replaced inside
COPY .docker/nginx/www.conf.template /etc/nginx/conf.d/default.conf.template

# entrypoint script
COPY .docker/nginx/entrypoint.sh .docker/wait-for-it.sh /usr/local/bin/

# custom json log format for access log
COPY .docker/nginx/log-json.conf /etc/nginx/log-json.conf


RUN set -e; \
    apt-get update; \
    # install gosu and other packages
    apt-get install -y --no-install-recommends \
        \authbind=2.1.2; \
    apt-get clean ;\
    rm -rf /var/lib/apt/lists/*; \
    # create application root directory, same directory exist in php image
    mkdir -p /var/www/public; \
    # scripts must be executable
    chmod +x /usr/local/bin/entrypoint.sh /usr/local/bin/wait-for-it.sh; \
    # create PID directory before running nginx ,so permissions can be set in entrypoint
    mkdir -p /var/run/nginx; \
    # set permissions to nginx directories
    chown -R nginx:nginx /var/run/nginx;

# set entrypoint script
ENTRYPOINT [ "/usr/local/bin/entrypoint.sh" ]

# run ru.sh script by default on startup
CMD [ "nginx", "-c", "/etc/nginx/nginx.conf" ]

####################
#### PROD BUILD ####
####################
FROM base as prod

# copy static assets
COPY ./public/ ./public/

####################
#### DEV BUILD #####
####################
FROM base as dev

ENV HOME=/home/app

ARG CONTAINER_UID
ARG CONTAINER_GID

# install all packages (incluging dev) and xdebug
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
    # allow binding to port 80 for user with uid in $CONTAINER_UID
    touch /etc/authbind/byport/80; \
    # change ownership of directories to app user
    chown -R app:app \
        ${HOME} \
        /etc/authbind/byport/80 \
        /etc/nginx/conf.d/default.conf \
        /var/cache/nginx \
        /var/run/nginx ; \
    # set execution bit for owner so authbind will allow binding
    chmod u+x /etc/authbind/byport/80 ;

# run this conatiner as user app (with UID and GID from build arg),
# file permissions are working well since container running user has same UID/GID as developer running it
USER app:app

# use authbind to allow binding below port 1024
CMD ["authbind", "--deep", "nginx", "-c", "/etc/nginx/nginx.conf" ]
