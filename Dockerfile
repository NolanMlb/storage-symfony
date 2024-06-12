FROM php:latest

# Install necessary PHP extensions
RUN apt-get update && apt-get install -y \
    libxslt1-dev \
    unzip \
    git \
    && docker-php-ext-install xsl

# Install Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash \
    && mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application code
COPY . .

# Install PHP dependencies
RUN composer install --no-scripts --no-interaction --optimize-autoloader

# Clear Symfony cache
RUN php bin/console cache:clear

# Expose port 8089
EXPOSE 8089

# Start Symfony server
CMD ["symfony", "server:start", "--no-tls", "--port=8089", "--allow-http"]