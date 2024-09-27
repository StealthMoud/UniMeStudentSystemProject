# Use the official PHP image as the base image
FROM php:8.3-apache

# Set the working directory
WORKDIR /var/www/html

# Install dependencies and required tools
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    gnupg \
    wget \
    lsb-release \
    ca-certificates \
    software-properties-common \
    && apt-get clean


# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd zip pdo pdo_mysql

# Install MySQLi extension
RUN docker-php-ext-install mysqli

# Install MongoDB extension for PHP
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Install MongoDB tools (mongosh)
RUN wget -qO - https://www.mongodb.org/static/pgp/server-5.0.asc | gpg --dearmor > /usr/share/keyrings/mongodb-archive-keyring.gpg && \
    echo "deb [signed-by=/usr/share/keyrings/mongodb-archive-keyring.gpg] http://repo.mongodb.org/apt/debian buster/mongodb-org/5.0 main" | tee /etc/apt/sources.list.d/mongodb-org-5.0.list && \
    apt-get update && \
    apt-get install -y mongodb-org-tools

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy the project files to the working directory
#COPY . /var/www/html

# Expose port 80 for the Apache server
EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]
