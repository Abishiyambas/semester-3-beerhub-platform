#!/usr/bin/env sh
DB_HOST="${DB_HOST:-mysql}"
DB_PORT="${DB_PORT:-3306}"

echo "Waiting for MySQL at ${DB_HOST}:${DB_PORT} ..."
until nc -z "$DB_HOST" "$DB_PORT"; do
    sleep 1
done
echo "MySQL is up."

# MySQL is up, running migrate:fresh
php artisan migrate:fresh --seed --force || {
    echo "mirgrate FAILED (continuing anyway)..."
}

# Start PHP-FPM and Nginx
php-fpm -D
nginx -g 'daemon off;'
