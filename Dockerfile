# Use PHP-FPM as base
FROM php:8.2-fpm

# Install Nginx and dependencies
RUN apt-get update && apt-get install -y \
    nginx \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --no-interaction --optimize-autoloader

# Copy Nginx configuration
COPY docker-compose/nginx/default.conf /etc/nginx/sites-available/default

# Create a start script that runs both PHP-FPM and Nginx
RUN echo '#!/bin/bash\n\
php-fpm -D\n\
nginx -g "daemon off;"\n\
' > /start.sh && chmod +x /start.sh

EXPOSE 80

# Start both services
CMD ["/start.sh"]
