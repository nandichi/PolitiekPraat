# Gebruik een basisimage met PHP en Apache
FROM php:8.1-apache

# Kopieer alle bestanden naar de servermap
COPY . /var/www/html/

# Stel de standaardpoort in
EXPOSE 8080

# Start de Apache-server
CMD ["apache2-foreground"]
