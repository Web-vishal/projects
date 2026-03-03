FROM php:8.2-apache

# Enable Apache modules
RUN a2enmod rewrite headers

# Install extension for MySQL if needed in the future
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Allow CORS by modifying apache config
RUN echo "Header set Access-Control-Allow-Origin \"*\"" >> /etc/apache2/apache2.conf
RUN echo "Header set Access-Control-Allow-Methods \"GET, POST, OPTIONS, PUT, DELETE\"" >> /etc/apache2/apache2.conf
RUN echo "Header set Access-Control-Allow-Headers \"Content-Type, Authorization\"" >> /etc/apache2/apache2.conf

# Set working directory
WORKDIR /var/www/html

# Copy backend files
COPY . /var/www/html/

# Expose port 80
EXPOSE 80
