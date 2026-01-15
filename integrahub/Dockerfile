FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql zip sockets \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Force PHP-FPM to listen on all interfaces
RUN echo "[www]" > /usr/local/etc/php-fpm.d/zzzz-force-docker.conf && \
    echo "listen = 9000" >> /usr/local/etc/php-fpm.d/zzzz-force-docker.conf

WORKDIR /var/www
