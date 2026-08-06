FROM php:8.2-apache

RUN docker-php-ext-install mysqli && a2enmod rewrite
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
COPY . /var/www/html
RUN composer install --no-dev --optimize-autoloader --no-interaction
COPY railway-start.sh /usr/local/bin/railway-start
RUN chmod +x /usr/local/bin/railway-start && chown -R www-data:www-data /var/www/html

EXPOSE 8080
CMD ["railway-start"]
