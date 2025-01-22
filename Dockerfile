# Gebruik een basisimage met PHP en Apache
FROM php:8.1-apache

# Installeer benodigde PHP extensies
RUN docker-php-ext-install pdo pdo_mysql

# Activeer Apache modules
RUN a2enmod rewrite

# Kopieer de Apache configuratie
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

# Maak de map voor de website
RUN mkdir -p /var/www/html/public

# Kopieer alle bestanden naar de servermap
COPY . /var/www/html/

# Stel de juiste bestandsrechten in
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Stel de werkmap in
WORKDIR /var/www/html

# Stel de standaardpoort in
EXPOSE 8080

# Start de Apache-server
CMD ["apache2-foreground"]
