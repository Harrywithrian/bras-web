# FROM docker/whalesay:latest
# LABEL Name=ibrweb Version=0.0.1
# RUN apt-get -y update && apt-get install -y fortunes
# CMD ["sh", "-c", "/usr/games/fortune -a | cowsay"]

FROM php:8.0-fpm-alpine

RUN docker-php-ext-install pdo pdo_mysql

# install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# install nodejs
RUN curl -sL https://deb.nodesource.com/setup_14.x | sudo -E bash -

# install php libs
# CMD ["composer install --ignore-platform-reqs", "npm install", "npm run prod", "copy .env.example .env", "php artisan key:generate"]