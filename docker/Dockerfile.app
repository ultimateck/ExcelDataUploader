FROM php:8.2-fpm

# Set the working directory to /var/www/
WORKDIR /var/www/

RUN apt update && apt install -y \
    git \
    curl \
    zip \
    zlib1g-dev \
    libpng-dev \
    libzip-dev

RUN docker-php-ext-install mysqli pdo pdo_mysql gd zip

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
    
# Copy the application code into the container
COPY . /var/www
RUN chown -R www-data:www-data /var/www/storage
# Run Composer install
RUN composer install

EXPOSE 9000
CMD ["php-fpm"]