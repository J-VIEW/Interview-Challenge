# syntax = docker/dockerfile:1.2
FROM php:8.1-apache

# Install system dependencies and PHP extensions
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libpng-dev libjpeg-dev libonig-dev libxml2-dev zip unzip git \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Render.com: Apache must listen on $PORT
# Render sets $PORT to 10000 by default
RUN sed -i 's/80/${PORT}/g' /etc/apache2/ports.conf /etc/apache2/sites-available/000-default.conf

# Set working directory
WORKDIR /var/www/html

# Copy app files
COPY . /var/www/html

# Copy Apache vhost config for clean URLs
COPY ./public/.htaccess /var/www/html/public/.htaccess

# Install Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Set permissions for logs (if needed)
RUN chown -R www-data:www-data /var/www/html/logs || true \
    && chmod -R 775 /var/www/html/logs || true

# Expose $PORT for Render
EXPOSE ${PORT}

# Start Apache in the foreground
CMD ["apache2-foreground"] 