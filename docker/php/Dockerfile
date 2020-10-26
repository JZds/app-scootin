FROM php:7.4-fpm
EXPOSE 9000
WORKDIR /app

RUN docker-php-ext-install bcmath pdo pdo_mysql && \
    apt-get update && \
    apt-get install -y curl gnupg zip unzip git && \
    apt-get update && \
    curl -sS https://getcomposer.org/installer | \
    php -- --install-dir=/usr/bin/ --filename=composer

CMD composer install && \
    composer dump-autoload && \
    php bin/console doctrine:schema:update --force && \
    php bin/console doctrine:fixtures:load && \
    php-fpm
