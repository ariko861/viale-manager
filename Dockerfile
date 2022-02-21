FROM php:7.4-fpm

RUN apt-get update -y && apt-get install -y libmcrypt-dev git zip curl libpq-dev

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql

WORKDIR /app
COPY . /app

COPY --chown=www-data:www-data . /app

USER www-data

RUN composer install

EXPOSE 9000
CMD ["php-fpm"]
