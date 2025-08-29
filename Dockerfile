FROM thecodingmachine/php:8.2-v4-apache

# Laravel docroot
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

WORKDIR /var/www/html
COPY . .

# Siapkan folder & izin untuk Laravel (pakai www-data)
RUN set -eux; \
    mkdir -p storage/logs bootstrap/cache; \
    chown -R www-data:www-data storage bootstrap/cache; \
    chmod -R 775 storage bootstrap/cache

# Composer install TANPA scripts (hindari artisan jalan saat build)
RUN set -eux; \
    curl -sS https://getcomposer.org/installer | php; \
    php composer.phar install --no-dev --optimize-autoloader --no-interaction --no-scripts; \
    rm composer.phar

# (opsional, biasanya sudah aktif di image ini)
# RUN a2enmod rewrite

EXPOSE 8080
ENV APACHE_PORT=8080
