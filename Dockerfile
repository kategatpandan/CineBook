FROM php:8.2-apache

# Install MySQL extensions
RUN docker-php-ext-install pdo_mysql mysqli

# Copy application files
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html/

# Enable Apache mod_rewrite (if needed)
RUN a2enmod rewrite

# Expose port 80
EXPOSE 80
