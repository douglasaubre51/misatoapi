# Stage 1: Build Frontend Assets
FROM node:20-alpine AS frontend
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# Stage 2: Final Application Image
FROM php:8.4-cli-alpine

# Install system runtime dependencies & PHP extensions (including PostgreSQL for Supabase)
RUN apk add --no-cache \
    postgresql-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_pgsql \
        pgsql \
        pdo_mysql \
        mbstring \
        zip \
        exif \
        pcntl \
        gd \
        intl \
        bcmath

# Install Composer from official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy manifest files first for layer caching
COPY composer.json composer.lock ./

# Install Composer dependencies (added --ignore-platform-req=php for PHP 8.4 compatibility)
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --ignore-platform-req=php

# Copy full application source code
COPY . .

# Copy compiled frontend assets from Stage 1
COPY --from=frontend /app/public/build ./public/build

# Optimizations & Package Discovery (Generates optimized autoloader and package manifest)
RUN composer dump-autoload --optimize --no-dev

# Set correct permissions for Laravel runtime directories
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
