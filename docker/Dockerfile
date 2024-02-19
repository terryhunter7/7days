FROM php:7.4-fpm

SHELL ["/bin/bash", "-o", "pipefail", "-c"]
ARG WITH_XDEBUG=false

ENV DEBIAN_FRONTEND=noninteractive
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
        libjpeg-dev libpng-dev libfreetype6-dev libicu-dev libyaml-dev libzip-dev libssh2-1-dev libssh2-1 \
        make mariadb-client && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN pecl install yaml-2.2.1 && \
    docker-php-ext-enable yaml && \
    docker-php-ext-install zip && \
    docker-php-ext-install exif && \
    docker-php-ext-install sockets

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/bin/composer

RUN docker-php-ext-install \
    intl \
    pcntl \
    pdo_mysql \
    && echo -e "no\nno\nno\n" | pecl install redis-5.2.1 \
    && echo "extension=redis.so" | tee /usr/local/etc/php/conf.d/redis.ini \
    && docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install gd

RUN pecl install ssh2 && docker-php-ext-enable ssh2

RUN echo "memory_limit=256M" >> /usr/local/etc/php/conf.d/project.ini \
    && echo "max_execution_time=30" >> /usr/local/etc/php/conf.d/project.ini \
    && echo "max_input_vars=10000" >> /usr/local/etc/php/conf.d/project.ini
