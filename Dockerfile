FROM thecodingmachine/php:8.2-v4-apache

# Laravel docroot
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

WORKDIR /var/www/html
COPY . .

# Siapkan permission SEBELUM composer install
# (pakai user root supaya bisa chown/chmod, lalu balik ke 'application')
USER root
RUN mkdir -p storage/logs bootstrap/cache && \
    chown -R application:application storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache
USER application

# Composer install TANPA scripts (hindari artisan jalan saat build)
RUN set -eux; \
    curl -sS https://getcomposer.org/installer | php; \
    php composer.phar install --no-dev --optimize-autoloader --no-interaction --no-scripts; \
    rm composer.phar

EXPOSE 8080
ENV APACHE_PORT=8080
