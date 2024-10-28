# Stage 1: Build stage with all dependencies
FROM php:8.3-fpm AS build-stage

# Install Nginx and necessary dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
    nginx \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    curl \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy application code and install dependencies
COPY . /var/www/html/appxpay-admin-management-development
WORKDIR /var/www/html/appxpay-admin-management-development

# Install Composer dependencies
RUN composer install --no-dev --optimize-autoloader

# Stage 2: Final lightweight image
FROM php:8.3-fpm-alpine AS production

# Install Nginx
RUN apk add --no-cache nginx

# Copy Nginx configuration
COPY nginx/default.conf /etc/nginx/conf.d/default.conf

# Copy application code from build stage
COPY --from=build-stage /var/www/html/appxpay-admin-management-development /var/www/html/appxpay-admin-management-development

# Set working directory and permissions
WORKDIR /var/www/html/appxpay-admin-management-development
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80

# Start PHP-FPM and Nginx
CMD ["sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]
