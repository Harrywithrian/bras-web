# FROM docker/whalesay:latest
# LABEL Name=ibrweb Version=0.0.1
# RUN apt-get -y update && apt-get install -y fortunes
# CMD ["sh", "-c", "/usr/games/fortune -a | cowsay"]

FROM php:8.0.12-fpm-alpine

RUN docker-php-ext-install pdo pdo_mysql

# install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# install nodejs
RUN apk add --update nodejs npm

# install php libs
# CMD ["composer install --ignore-platform-reqs", "npm install", "npm run prod"]