FROM php:8.2-fpm

# Install system dependencies REQUIRED for mbstring + postgres
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libonig-dev \
    pkg-config \
    autoconf \
    make \
    git \
    unzip \
    zip \
    && docker-php-ext-install pdo_pgsql pgsql mbstring bcmath

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy ONLY backend code
COPY backend/ .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Expose port for Render
EXPOSE 8000

# Start Laravel
CMD php artisan serve --host=0.0.0.0 --port=8000
