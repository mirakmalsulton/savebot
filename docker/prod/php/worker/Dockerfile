FROM php:8.3-cli

# Расширения PHP
ADD https://raw.githubusercontent.com/mlocati/docker-php-extension-installer/master/install-php-extensions /usr/local/bin/
RUN chmod uga+x /usr/local/bin/install-php-extensions && sync
RUN install-php-extensions pdo_mysql mcrypt bcmath zip gd

# Системные пакеты
RUN apt-get update && apt-get install -y --no-install-recommends \
    supervisor \
    python3 \
    python3-pip \
    ffmpeg \
    curl \
    nodejs \
    npm \
    && rm -rf /var/lib/apt/lists/*

# Устанавливаем всё через pip в системную директорию
# --break-system-packages — это норма для Docker-контейнера
RUN pip install --no-cache-dir --break-system-packages \
    yt-dlp \
    websockets \
    brotli \
    cryptography \
    certifi

# КРИТИЧЕСКИЙ МОМЕНТ: Прописываем пути, чтобы yt-dlp видел либы
ENV PYTHONPATH="/usr/local/lib/python3.13/dist-packages"

# Даем права
RUN chmod 755 /usr/local/bin/yt-dlp && \
    chown www-data:www-data /usr/local/bin/yt-dlp

# Твои конфиги
COPY ./docker/prod/php/worker/conf.d /usr/local/etc/php/conf.d
RUN mkdir -p /etc/supervisor.d
COPY ./docker/prod/php/worker/supervisord.conf /etc/supervisord.conf

WORKDIR /var/www/app

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
