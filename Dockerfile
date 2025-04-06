FROM php:8.2-apache

# Enable mod_rewrite (optional but common)
RUN a2enmod rewrite

# Set googleads.php as the default file
RUN echo "DirectoryIndex googleads.php" >> /etc/apache2/apache2.conf

# Copy all your PHP code to the Apache web root
COPY . /var/www/html/

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
