# Gebruik een basisimage met PHP en Apache
FROM php:8.1-apache

# Installeer benodigde PHP extensies
RUN docker-php-ext-install pdo pdo_mysql

# Activeer Apache modules
RUN a2enmod rewrite

# Configureer Apache
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf \
    && sed -i 's!AllowOverride None!AllowOverride All!g' /etc/apache2/apache2.conf

# Maak de benodigde mappen
RUN mkdir -p /var/www/html/public

# Kopieer eerst alleen de public bestanden
COPY public /var/www/html/public/

# Kopieer daarna de rest van de bestanden
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
