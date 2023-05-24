FROM php:8.1-fpm

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

ARG PUID=1001
ENV PUID ${PUID}
ARG PGID=1001
ENV PGID ${PGID}

RUN groupadd -g ${PGID} docker && \
    useradd -u ${PUID} -g docker -m docker -G docker && \
    usermod -p "*" docker -s /bin/bash

RUN apt-get update && apt-get upgrade -y && apt-get install apt-utils -y \
    && install-php-extensions zip gd xdebug pdo pdo_mysql \
    && docker-php-ext-enable xdebug \
    && docker-php-source delete \
    && apt-get autoremove --purge -y \
    && apt-get autoclean -y \
    && apt-get clean -y

EXPOSE 9000
