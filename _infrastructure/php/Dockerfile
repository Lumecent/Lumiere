FROM php:8.3-fpm

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www/app

RUN apt-get update && apt-get upgrade -y && apt-get install apt-utils -y \
    && install-php-extensions zip gd xdebug pdo pdo_mysql openssl \
    && docker-php-ext-enable xdebug \
    && docker-php-source delete \
    && apt-get autoremove --purge -y \
    && apt-get autoclean -y \
    && apt-get clean -y

RUN chown -R www-data:www-data /var/www/app

RUN chmod -R 755 /var/www/app

EXPOSE 9000
