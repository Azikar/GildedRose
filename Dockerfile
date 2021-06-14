FROM php:7.3-fpm
#COPY .docker/php/php.ini /usr/local/etc/php/php.ini

RUN apt-get update && apt-get install -y libxml2-dev libzip-dev libpng-dev zip unzip libonig-dev
RUN docker-php-ext-install mbstring intl pdo_mysql

WORKDIR /app/app

RUN apt-get update && apt-get install -y git
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

#COPY ["app/composer.lock", "app/composer.json", "/app/"]

#ARG USER_ID
#ARG GROUP_ID
#USER $USER_ID:$GROUP_ID