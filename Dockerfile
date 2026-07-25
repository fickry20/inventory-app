# 1. Gunakan PHP 8.2 FPM versi ringan (Alpine)
FROM php:8.2-fpm-alpine

# 2. Install dependensi sistem & extension PHP yang dibutuhkan Laravel
RUN apk add --no-cache \
    zip \
    unzip \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    libxml2-dev \
    git \
    curl \
    nginx

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring gd xml

# 3. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Tentukan folder kerja di dalam container
WORKDIR /var/www/html

# 5. Copy seluruh isi project Laravel ke dalam container
COPY . .

# 6. Install dependensi composer
RUN composer install --no-dev --optimize-autoloader

# 7. Beri izin akses ke folder storage dan cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 8. Konfigurasi Nginx sederhana
RUN mkdir -p /run/nginx
COPY docker/nginx.conf /etc/nginx/http.d/default.conf

# 9. Jalankan Nginx dan PHP-FPM secara bersamaan
EXPOSE 80
CMD ["sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]