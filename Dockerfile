FROM php:8.0-apache
RUN a2enmod rewrite
RUN docker-php-ext-install pdo_mysql mysqli