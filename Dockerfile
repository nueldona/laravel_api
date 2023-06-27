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

# Update the port number to 8080
EXPOSE 8080

# Start the application
CMD [ "php", "artisan", "serve", "--host=0.0.0.0", "--port=8080" ]
