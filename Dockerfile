FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libxml2-dev libzip-dev zip \
    && docker-php-ext-install pdo_mysql intl

RUN a2enmod rewrite

COPY . /var/www/html
WORKDIR /var/www/html

# VirtualHost Symfony
COPY apache.conf /etc/apache2/sites-available/000-default.conf

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install --no-interaction

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
