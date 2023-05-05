ARG DOCKER_PHP_VERSION

FROM php:${DOCKER_PHP_VERSION}-fpm-alpine

RUN apk add --update --no-cache icu-libs \
    libintl \
    imagemagick \
    curl \
    bash

#  Install php extensions
RUN curl -sSL https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions -o - | sh -s \
    gd \
    xdebug \
    mysqli \
    opcache \
    xsl \
    bcmath \
    gd \
    gettext \
    intl \
    opcache \
    pcntl \
    soap \
    zip \
    calendar \
    sockets \
    pdo_mysql \
    exif

# Clean
RUN rm -rf /var/cache/apk/* && docker-php-source delete

USER root

RUN cd /var/www/html && curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

USER www-data:www-data

WORKDIR /var/www/

# CMD bash -c "cd api.ru && composer install --no-interaction && php yii migrate --interactive=0"