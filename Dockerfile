FROM php:8.2.24-cli-alpine AS builder

RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql bcmath

COPY --from=composer:2.7.7 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install --no-dev --no-scripts --prefer-dist --no-autoloader --no-interaction

COPY . .

RUN composer dump-autoload --optimize --no-dev

FROM php:8.2.24-cli-alpine AS runner

RUN apk add --no-cache \
    curl \
    libpng \
    libxml2 \
    && docker-php-ext-install pdo_mysql bcmath

WORKDIR /var/www

COPY --from=builder /app /var/www

RUN mkdir -p /var/www/storage/framework/cache \
             /var/www/storage/framework/sessions \
             /var/www/storage/framework/views \
             /var/www/storage/logs \
             /var/www/bootstrap/cache \
             /var/www/database \
    && touch /var/www/database/database.sqlite \
    && chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache /var/www/database \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache /var/www/database

USER www-data

HEALTHCHECK --interval=30s --timeout=5s --start-period=10s --retries=3 \
    CMD curl -f http://127.0.0.1:8000/up || curl -f http://127.0.0.1:8000/api/tugas || curl -f http://127.0.0.1:8000/ || exit 1

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]