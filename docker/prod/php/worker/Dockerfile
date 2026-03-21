FROM php:8.3-cli-alpine3.21

ADD https://raw.githubusercontent.com/mlocati/docker-php-extension-installer/master/install-php-extensions /usr/local/bin/
RUN chmod uga+x /usr/local/bin/install-php-extensions && sync
RUN install-php-extensions pdo_mysql mcrypt

COPY ./docker/prod/php/worker/conf.d /usr/local/etc/php/conf.d

RUN apk --no-cache update && \
    apk add --no-cache --repository=http://dl-cdn.alpinelinux.org/alpine/edge/community/ supervisor
RUN mkdir -p /etc/supervisor.d
COPY ./docker/prod/php/worker/supervisord.conf /etc/supervisord.conf

WORKDIR /var/www/app

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
