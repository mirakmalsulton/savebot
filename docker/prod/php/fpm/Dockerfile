FROM php:8.3-fpm-alpine3.21

ADD https://raw.githubusercontent.com/mlocati/docker-php-extension-installer/master/install-php-extensions /usr/local/bin/
RUN chmod uga+x /usr/local/bin/install-php-extensions && sync
RUN install-php-extensions pdo_mysql mcrypt xdebug

COPY ./docker/dev/php/fpm/conf.d /usr/local/etc/php/conf.d

WORKDIR /var/www/app
