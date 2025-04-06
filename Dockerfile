FROM php:8.2-apache

# Enable mod_rewrite (optional, useful for routing)
RUN a2enmod rewrite

# Copy project files to the container
COPY . /var/www/html/

# Set permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port 80 (Render uses this by default)
EXPOSE 80
