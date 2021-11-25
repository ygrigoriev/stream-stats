FROM php:8.0.12-fpm

RUN apt-get update \
    && apt-get -y --no-install-recommends install curl \
    && pecl install xdebug-3.0.4 \
    && docker-php-ext-enable xdebug \
    && docker-php-ext-install pdo_mysql \
    # Next line is for proper composer work only
    && apt-get -y --no-install-recommends install unzip zip \
    && curl -sS https://getcomposer.org/installer | php -- --filename=composer --install-dir=/usr/local/bin

# Clean caches
RUN apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

WORKDIR /application
CMD ["php-fpm"]
