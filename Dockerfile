FROM php:7.4.1-apache

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN apt-get update
RUN apt-get install -y libmcrypt-dev openssl libzip-dev

RUN apt-get update && apt-get install -y \
        libfreetype6-dev \

        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng-dev \
        zlib1g-dev \
        libxml2-dev \
        libzip-dev \
        libonig-dev \
        graphviz \
        cron \
        supervisor \

    && docker-php-ext-configure gd \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install zip \
    && docker-php-source delete

#Install nodejs
RUN apt-get -y install curl gnupg
RUN curl -sL https://deb.nodesource.com/setup_11.x  | bash -
RUN apt-get -y install nodejs

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /var/www/html

COPY ./php.ini /usr/local/etc/php/conf.d/upload.php.ini

RUN mv /var/www/html/.env.docker /var/www/html/.env

RUN composer install
RUN npm install
RUN npm run prod

USER root

COPY ./crontab /etc/crontab
RUN chmod 0644 /etc/crontab
RUN service cron start

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN php /var/www/html/artisan storage:link
RUN php /var/www/html/artisan vendor:publish --tag=public --force

RUN a2enmod rewrite && a2enmod expires
RUN service apache2 restart

RUN chown 755 /var/www/html/bootstrap/cache
RUN php /var/www/html/artisan config:clear
RUN php /var/www/html/artisan config:cache
RUN php /var/www/html/artisan view:clear
RUN php /var/www/html/artisan route:clear

RUN chown -R www-data:www-data /var/www/

ENTRYPOINT cd /var/www/html/ && php artisan migrate --force && /usr/bin/supervisord -c /var/www/html/supervisord.conf -n
