FROM php:8.2-apache

RUN docker-php-ext-install mysqli mbstring
RUN a2enmod rewrite

WORKDIR /var/www/html
COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html/application/cache /var/www/html/application/logs \
    && find /var/www/html -type d -exec chmod 755 {} \; \
    && find /var/www/html -type f -exec chmod 644 {} \;

COPY docker/entrypoint.sh /usr/local/bin/gameina-entrypoint
RUN chmod +x /usr/local/bin/gameina-entrypoint

EXPOSE 8080
ENTRYPOINT ["gameina-entrypoint"]
