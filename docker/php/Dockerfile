# Use the official PHP image with Apache
FROM php:8.2-apache

# Install necessary PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Enable Apache rewrite module
RUN a2enmod rewrite

# Copy the application files to the container
COPY ../ /var/www/html

# Set the working directory
WORKDIR /var/www/html

# Expose port 80
EXPOSE 80