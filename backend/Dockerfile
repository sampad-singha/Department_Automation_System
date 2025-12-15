FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libonig-dev \
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

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# Expose port Render expects
EXPOSE 8000

# Start Laravel
CMD php artisan serve --host=0.0.0.0 --port=8000
