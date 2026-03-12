#!/bin/sh
set -e

echo "==> Optimizando Laravel..."
php artisan config:cache
php artisan route:cache
php artisan event:cache

echo "==> Corriendo migraciones..."
php artisan migrate --force

echo "==> Iniciando servicios..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf