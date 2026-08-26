FROM php:8.2-cli

RUN apt-get update \
    && apt-get install -y --no-install-recommends default-mysql-client libonig-dev \
    && docker-php-ext-install mysqli mbstring \
    && apt-get purge -y --auto-remove libonig-dev \
    && rm -rf /var/lib/apt/lists/*
WORKDIR /var/www/html
COPY . /var/www/html/

COPY docker/init-mysql.sh /usr/local/bin/init-mysql
RUN chmod +x /usr/local/bin/init-mysql

RUN chown -R www-data:www-data /var/www/html/application/cache /var/www/html/application/logs \
    && find /var/www/html -type d -exec chmod 755 {} \; \
    && find /var/www/html -type f -exec chmod 644 {} \;

EXPOSE 8080
CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-8080} -t /var/www/html /var/www/html/router.php"]
