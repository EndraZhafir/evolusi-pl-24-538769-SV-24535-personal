#!/usr/bin/env bash

set -e

cd /var/www/aplikasi

php artisan down --retry=60

git pull origin main

composer install --no-dev --optimize-autoloader

php artisan migrate --force

php artisan config:cache
php artisan route:cache
php artisan view:cache

php artisan queue:restart

php artisan up
