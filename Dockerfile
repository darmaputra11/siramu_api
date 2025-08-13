# Gunakan image PHP 8.2 dengan Apache (lebih gampang)
FROM php:8.2-apache

# Install ekstensi PHP yang dibutuhkan Laravel
RUN docker-php-ext-install pdo pdo_mysql

# Aktifkan mod_rewrite Apache (buat Laravel routing)
RUN a2enmod rewrite

# Copy semua file Laravel ke container
COPY . /var/www/html

# Set permission untuk storage & bootstrap/cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Atur Apache agar Laravel bisa handle semua request
RUN echo "<Directory /var/www/html/public>\n\
    AllowOverride All\n\
</Directory>" >> /etc/apache2/apache2.conf

# Jalankan Apache di port 80
EXPOSE 80

# Command default
CMD ["apache2-foreground"]
