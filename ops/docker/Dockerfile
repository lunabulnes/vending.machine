FROM php:7.4-fpm-alpine3.11

RUN curl -sSk https://getcomposer.org/installer | php -- && \
    mv composer.phar /usr/local/bin/composer

WORKDIR /home/wwwroot/vending.machine

COPY ./composer.* ./
RUN composer install

CMD ["php-fpm"]
