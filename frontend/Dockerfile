FROM php:8.2-apache
RUN apt-get update && apt-get install -y \
    curl \
    libcurl4-openssl-dev
RUN docker-php-ext-install curl
RUN a2enmod proxy proxy_http rewrite
COPY ./apache.conf /etc/apache2/sites-available/000-default.conf
