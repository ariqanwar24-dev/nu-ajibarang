# Production image: Laravel (Blade + Vite assets) behind nginx and php-fpm in
# one container. Traefik terminates TLS in front of it.
#
#   /          -> public/ (built Vite assets, then Laravel via php-fpm)
#   /storage   -> public/storage symlink into storage/app/public, recreated at boot
#
# PHP 8.4: symfony 8.x in composer.lock requires >=8.4.1.

# ---------- php base ----------
FROM php:8.4-fpm-alpine AS php-base
RUN docker-php-ext-install pdo_mysql \
    && docker-php-ext-enable opcache

# ---------- php dependencies ----------
FROM php-base AS vendor
RUN apk add --no-cache git unzip
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-interaction --prefer-dist --no-dev \
    --no-autoloader --no-scripts
COPY . .
RUN composer dump-autoload --optimize --no-dev

# ---------- frontend assets ----------
FROM node:22-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
RUN npm run build

# ---------- runtime ----------
FROM php-base

RUN apk add --no-cache nginx supervisor && rm -rf /var/cache/apk/*

COPY docker/nginx/production.conf /etc/nginx/http.d/default.conf
COPY docker/php/php.ini /usr/local/etc/php/conf.d/php.ini
COPY docker/supervisor/production.conf /etc/supervisor/conf.d/supervisord.conf

WORKDIR /var/www

COPY --from=vendor /app/vendor ./vendor
COPY . .
COPY --from=assets /app/public/build ./public/build

COPY docker/entrypoint.sh /usr/local/bin/entrypoint
RUN chmod +x /usr/local/bin/entrypoint \
    && mkdir -p /var/log/supervisor /run/nginx \
        storage/app/public \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint"]
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
