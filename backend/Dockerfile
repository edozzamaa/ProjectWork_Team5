FROM php:8.2-apache

RUN apt-get update \
	&& apt-get install -y --no-install-recommends git unzip \
	&& rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install mysqli
RUN a2enmod rewrite
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
COPY ./conf/apache.conf /etc/apache2/sites-available/000-default.conf
