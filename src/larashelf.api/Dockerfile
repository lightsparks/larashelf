FROM php:8.4-fpm

# system libs + PHP extensions
RUN apt-get update \
 && apt-get install -y git unzip libicu-dev libzip-dev libpng-dev \
 && docker-php-ext-install pdo_mysql intl zip gd \
 && rm -rf /var/lib/apt/lists/*

# composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# entrypoint (handles composer, key, migrate, then php-fpm)
COPY docker/api/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 9000
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
