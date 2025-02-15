FROM php:7.4-apache

# Install any necessary dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Install any necessary PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Copy the PHP application files to the container
COPY . /var/www/html

# Set the working directory for the container
WORKDIR /var/www/html

# Expose the container's port 80
EXPOSE 80

# Start the Apache web server
CMD ["apache2-foreground"]
