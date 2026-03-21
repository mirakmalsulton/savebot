FROM php:8.3-cli-alpine3.21

ADD https://raw.githubusercontent.com/mlocati/docker-php-extension-installer/master/install-php-extensions /usr/local/bin/
RUN chmod uga+x /usr/local/bin/install-php-extensions && sync
RUN install-php-extensions pdo_mysql mcrypt

COPY ./docker/dev/php/cli/conf.d /usr/local/etc/php/conf.d

RUN curl -s https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

WORKDIR /var/www/app
