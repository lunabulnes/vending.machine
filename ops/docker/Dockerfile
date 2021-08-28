FROM php:7.4-fpm-alpine3.11 as base

RUN curl -sSk https://getcomposer.org/installer | php -- && \
    mv composer.phar /usr/local/bin/composer

WORKDIR /home/wwwroot/vending.machine

FROM base as prod

RUN echo "To be implemented"
CMD ["php-fpm"]

FROM base as dev

CMD ["./ops/scripts/run-dev.sh"]

