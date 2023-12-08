# Use PHP 7.4-FPM as base image
FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www/html

# Install CA certificates to enable SSL verification
RUN apt-get update && apt-get install -y --no-install-recommends \
    ca-certificates && \
    rm -rf /var/lib/apt/lists/*

# Copy your application code into the container with correct permissions
COPY --chown=www-data:www-data . /var/www/html

# Install any additional PHP extensions or dependencies if needed
RUN docker-php-ext-install pdo_mysql

# Expose port if necessary (e.g., for PHP-FPM)
EXPOSE 9000

# Start PHP-FPM (if necessary for your setup)
CMD ["php-fpm"]
