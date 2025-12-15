# ===== Build from official PHP image with CLI
FROM php:8.2-fpm

# ===== Install system tools & PHP extensions
RUN apt-get update && apt-get install -y \
        libpq-dev \
        git \
        unzip \
        zip \
    && docker-php-ext-install pdo_pgsql pgsql \
    && docker-php-ext-install bcmath mbstring

# ===== Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ===== Set working directory
WORKDIR /var/www

# ===== Copy backend code
COPY . .

# ===== Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# ===== Expose port 8000
EXPOSE 8000

# ===== Run Laravel
CMD php artisan serve --host=0.0.0.0 --port=8000
