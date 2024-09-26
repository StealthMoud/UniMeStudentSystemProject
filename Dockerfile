# Use the official PHP image as the base image
FROM php:7.4-apache

# Set the working directory
WORKDIR /var/www/html

# Install necessary extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd mysqli pdo pdo_mysql zip bcmath

# Install MongoDB extension
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy composer from the composer image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy the project files to the working directory
COPY . /var/www/html

# Run composer install with ignored platform requirements
RUN composer install --ignore-platform-req=ext-bcmath --ignore-platform-req=ext-mongodb

# Expose port 80
EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]
