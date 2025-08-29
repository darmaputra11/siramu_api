FROM thecodingmachine/php:8.2-v4-apache

# Cukup set docroot; image ini sudah siap untuk Laravel
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

WORKDIR /var/www/html
COPY . .

# Composer install (tanpa dev) — tidak butuh root
RUN set -eux; \
    curl -sS https://getcomposer.org/installer | php; \
    php composer.phar install --no-dev --optimize-autoloader --no-interaction; \
    rm composer.phar; \
    php artisan config:clear || true; \
    php artisan route:clear || true; \
    php artisan view:clear || true

# Permission Laravel
RUN chown -R www-data:www-data storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

EXPOSE 8080
ENV APACHE_PORT=8080
