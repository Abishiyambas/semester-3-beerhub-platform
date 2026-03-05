FROM php:8.4-fpm

# Install system dependencies, including netcat for wait script, and nginx
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libonig-dev \
    libzip-dev \
    zip \
    nginx \
    netcat-openbsd \
    && docker-php-ext-install pdo pdo_mysql zip \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy Laravel files
COPY . .

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Nginx config for Laravel
RUN rm -f /etc/nginx/sites-enabled/default \
    && echo 'server { \
    listen 80; \
    root /var/www/html/public; \
    index index.php index.html; \
    server_name _; \
    location / { \
    try_files $uri $uri/ /index.php?$query_string; \
    } \
    location ~ \.php$ { \
    include snippets/fastcgi-php.conf; \
    fastcgi_pass 127.0.0.1:9000; \
    } \
    location ~ /\.ht { \
    deny all; \
    } \
    }' > /etc/nginx/sites-available/laravel \
    && ln -s /etc/nginx/sites-available/laravel /etc/nginx/sites-enabled/laravel

RUN chown -R www-data:www-data storage bootstrap/cache

# Copy entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
