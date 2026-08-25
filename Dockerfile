FROM php:8.2-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends libonig-dev \
    && docker-php-ext-install mysqli mbstring \
    && apt-get purge -y --auto-remove libonig-dev \
    && rm -rf /var/lib/apt/lists/*
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
