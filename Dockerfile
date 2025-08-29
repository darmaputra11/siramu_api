FROM thecodingmachine/php:8.3-v4-apache

# Install dependensi tambahan
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git \
 && docker-php-ext-install pdo_mysql zip \
 && rm -rf /var/lib/apt/lists/*

# Set document root ke /public (Laravel)
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html
COPY . .

# Install composer dependencies (tanpa dev)
RUN set -eux; \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer; \
    composer install --no-dev --optimize-autoloader --no-interaction; \
    php artisan config:clear || true; \
    php artisan route:clear || true; \
    php artisan view:clear || true

# Permission untuk Laravel
RUN chown -R www-data:www-data storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

EXPOSE 8080
ENV APACHE_PORT=8080
