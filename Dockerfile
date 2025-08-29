FROM thecodingmachine/php:8.2-v4-apache

# Laravel serve dari /public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

WORKDIR /var/www/html
COPY . .

# Jalankan langkah yang butuh izin sebagai root
USER root
RUN set -eux; \
    # install composer secara global
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer; \
    # siapkan folder yang diperlukan Laravel
    mkdir -p storage/logs bootstrap/cache; \
    # install dependency tanpa menjalankan composer scripts (hindari artisan saat build)
    composer install --no-dev --optimize-autoloader --no-interaction --no-scripts; \
    # berikan kepemilikan ke www-data agar runtime Apache bisa tulis
    chown -R www-data:www-data storage bootstrap/cache vendor; \
    chmod -R 775 storage bootstrap/cache

# (opsional) kembali ke user default image — tidak wajib
# USER www-data

EXPOSE 8080
ENV APACHE_PORT=8080
