FROM php:8.2-fpm

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

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# 👇 COPY CURRENT DIRECTORY (backend)
COPY . .

RUN composer install --no-dev --optimize-autoloader

EXPOSE 8000

CMD php artisan serve --host=0.0.0.0 --port=8000
