# Use an official PHP runtime as the base image
FROM php:8.1-apache

# Install dependencies
RUN apt-get update && \
    apt-get install -y git zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set the working directory
WORKDIR /app

# Copy project files
COPY . /app

# Install project dependencies
RUN composer install --no-dev --no-scripts

# Run additional setup commands if needed

# Start the application
CMD [ "php", "artisan", "serve", "--host=0.0.0.0" ]

# # Set the working directory in the container
# WORKDIR /var/www/html

# # Copy the composer.json and composer.lock files to the container
# COPY composer.json composer.lock ./

# # Install PHP dependencies
# RUN composer install

# # Copy the remaining Laravel application files to the container
# COPY . .

# # Set the appropriate permissions
# RUN chown -R www-data:www-data storage bootstrap/cache

# # Expose port 80
# EXPOSE 80

# # Start the Apache server
# CMD ["php", "artisan", "serve"]
