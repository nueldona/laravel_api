# Use an official PHP runtime as the base image
FROM php:8.0-apache

# Set the working directory in the container
WORKDIR /var/www/html

# Copy the composer.json and composer.lock files to the container
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install

# Copy the remaining Laravel application files to the container
COPY . .

# Set the appropriate permissions
RUN chown -R www-data:www-data storage bootstrap/cache

# Expose port 80
EXPOSE 80

# Start the Apache server
CMD ["php", "artisan", "serve"]
