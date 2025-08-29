FROM thecodingmachine/php:8.3-v4-apache

# Cukup set document root; thecodingmachine image akan handle sendiri
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

WORKDIR /var/www/html
COPY . .

# Composer install (tanpa dev)
RUN set -eux; \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer; \
    composer install --no-dev --optimize-autoloader --no-interaction; \
    php artisan config:clear || true; \
    php artisan route:clear || true; \
    php artisan view:clear || true

# Permission Laravel
RUN chown -R www-data:www-data storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

EXPOSE 8080
ENV APACHE_PORT=8080
