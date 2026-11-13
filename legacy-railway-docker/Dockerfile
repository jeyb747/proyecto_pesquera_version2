FROM php:8.2-apache

RUN docker-php-ext-install mysqli && a2enmod rewrite \
    && sed -i 's/Listen 80/Listen ${PORT}/' /etc/apache2/ports.conf \
    && sed -i 's/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/' /etc/apache2/sites-available/000-default.conf
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
COPY composer.json ./
COPY api/ ./api/
COPY css/ ./css/
COPY imagenes/ ./imagenes/
COPY includes/ ./includes/
COPY js/ ./js/
COPY paginas/ ./paginas/
COPY php/ ./php/
COPY version_final/ ./version_final/
COPY index.php ./index.php
RUN composer install --no-dev --optimize-autoloader --no-interaction
COPY legacy-railway-docker/railway-start.sh /usr/local/bin/railway-start
RUN chmod +x /usr/local/bin/railway-start \
    && mkdir -p /var/run/apache2 /var/lock/apache2 \
    && chown -R www-data:www-data /var/www/html /var/run/apache2 /var/lock/apache2 /var/log/apache2

EXPOSE 8080
USER www-data
CMD ["railway-start"]
