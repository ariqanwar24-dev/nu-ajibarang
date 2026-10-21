#!/bin/sh
# Runs before supervisord. Everything here is safe to repeat on every boot.
set -e

cd /var/www

# storage/app is a mounted volume; a fresh host starts empty.
mkdir -p storage/app/public \
         storage/app/private \
         storage/framework/cache/data \
         storage/framework/sessions \
         storage/framework/views \
         storage/logs \
         bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Views reference uploads through asset('storage/...').
php artisan storage:link --force

# Set RUN_MIGRATIONS=false to deploy without touching the database.
if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
  echo "==> php artisan migrate --force"
  php artisan migrate --force
fi

# composer ran with --no-scripts, so package discovery never happened.
php artisan package:discover --ansi

# Cached at boot so the cache holds the real environment, not the build's.
php artisan config:cache
php artisan route:cache
php artisan view:cache

# artisan ran as root above; php-fpm serves as www-data and must own these.
chown -R www-data:www-data storage bootstrap/cache

exec "$@"
