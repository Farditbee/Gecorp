# Gunakan PHP 8.2 FPM image
FROM php:8.2-fpm

# Install system dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    git curl zip unzip \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev libzip-dev libicu-dev \
    procps \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl gd intl

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy semua source code
COPY . .

RUN mkdir -p /var/www/public/barcodes \
    && chown -R www-data:www-data /var/www/public/barcodes \
    && chmod -R 775 /var/www/public/barcodes

RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Install dependencies
RUN composer install --no-dev --prefer-dist --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 9000

# Gunakan php-fpm (artinya nginx akan handle request ke Laravel)
CMD ["php-fpm"]
