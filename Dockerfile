# Laravel 12 on Render (PHP 8.3 + nginx). Deploys the Jan Aushadhi store.
FROM php:8.3-fpm-alpine

# System deps + PHP extensions (pgsql for Neon, etc.)
RUN apk add --no-cache nginx supervisor postgresql-dev icu-dev oniguruma-dev libzip-dev zip unzip git \
 && docker-php-ext-install pdo pdo_pgsql pgsql mbstring bcmath intl zip opcache

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# Install PHP deps (no dev), build front-end if node assets exist
RUN composer install --no-dev --optimize-autoloader --no-interaction \
 && mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache \
 && chown -R www-data:www-data storage bootstrap/cache

# nginx + php-fpm config
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisord.conf

# Render provides $PORT; nginx listens on it
ENV PORT=8080
EXPOSE 8080

# On boot: cache config/routes/views, migrate, then serve
CMD sh -c "php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan migrate --force && supervisord -c /etc/supervisord.conf"
