FROM php:7.4.7-cli-buster

COPY --from=composer:1.10.4 /usr/bin/composer /usr/bin/composer

RUN apt update && apt install -y git

