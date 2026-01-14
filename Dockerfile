# Use PHP-FPM as base
FROM php:8.2-fpm

# Install Nginx, PostgreSQL client and dependencies
RUN apt-get update && apt-get install -y \
    nginx \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions for Laravel
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage \
    && chmod -R 755 /var/www/bootstrap/cache

# Generate Laravel key if .env doesn't exist
RUN if [ ! -f ".env" ]; then \
    cp .env.example .env && php artisan key:generate; \
    fi

# Copy Nginx configuration
COPY docker-compose/nginx/default.conf /etc/nginx/sites-available/default

# Update Nginx config for same container
RUN sed -i 's/fastcgi_pass app:9000;/fastcgi_pass 127.0.0.1:9000;/g' /etc/nginx/sites-available/default

# Copy start script
COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

EXPOSE 80

CMD ["/usr/local/bin/start.sh"]
