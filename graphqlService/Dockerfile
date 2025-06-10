FROM php:8.2-apache

RUN apt-get update && apt-get install --reinstall -y \
    curl \
    zip \
    unzip \
    libzip-dev \
    ca-certificates \
 && update-ca-certificates \
 && docker-php-ext-install zip pdo_mysql


RUN a2enmod rewrite

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html

COPY . .

RUN mkdir -p /usr/local/etc/ssl/certs && \
    curl -o /usr/local/etc/ssl/certs/cacert.pem https://curl.se/ca/cacert.pem && \
    cp /usr/local/etc/ssl/certs/cacert.pem /usr/local/share/ca-certificates/cacert.pem && \
    update-ca-certificates


ENV SSL_CERT_FILE=/usr/local/etc/ssl/certs/cacert.pem
ENV COMPOSER_CAFILE=/usr/local/etc/ssl/certs/cacert.pem
ENV PHP_INI_SCAN_DIR=/usr/local/etc/php/conf.d

RUN echo "openssl.cafile=/usr/local/etc/ssl/certs/cacert.pem" > /usr/local/etc/php/conf.d/cacert.ini


RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ENV SSL_CERT_FILE=/usr/local/share/ca-certificates/cacert.pem

RUN composer install

RUN composer require predis/predis

RUN composer require nuwave/lighthouse

RUN php artisan vendor:publish --tag=lighthouse-schema

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

CMD ["apache2-foreground"]
