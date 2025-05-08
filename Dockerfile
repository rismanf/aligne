# Tahap 1: Build frontend assets
FROM node:18 as node-builder

WORKDIR /app
COPY . .

RUN npm install && npm run build

# Tahap 2: PHP + FrankenPHP
FROM dunglas/frankenphp:1.4.4-php8.4-bookworm

# Install dependencies PHP
RUN apt-get update && apt-get install -y \
    git libpq-dev libmcrypt-dev zip unzip libjpeg-dev libpng-dev libzip-dev \
    libxml2-dev libonig-dev libcurl4-openssl-dev libldap2-dev && \
    ln -s /usr/lib/x86_64-linux-gnu/libldap.so /usr/lib/libldap.so && \
    rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install gd pgsql opcache pdo pdo_pgsql zip xml mbstring curl ldap

WORKDIR /app

# Copy semua source code
COPY . .

# Copy hasil build frontend dari tahap sebelumnya
COPY --from=node-builder /app/public/build ./public/build

# Install Composer & dependencies
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --optimize-autoloader

RUN chmod -R 777 storage bootstrap/cache
