FROM php:8.2-apache

# Install required extensions and SSL
RUN apt-get update && apt-get install -y \
    openssl \
    ca-certificates \
    && update-ca-certificates

# Install MySQL extensions
RUN docker-php-ext-install pdo_mysql mysqli

# Copy application files
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html/

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Expose port 80
EXPOSE 80
