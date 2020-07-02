FROM php:7.2-apache


# From https://laravel-news.com/install-microsoft-sql-drivers-php-7-docker
# Microsoft SQL Server Prerequisites
ENV ACCEPT_EULA=Y
RUN apt-get update && apt-get install gnupg2 libonig-dev git -y
RUN apt-get update \
    && curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add - \
    && curl https://packages.microsoft.com/config/debian/9/prod.list \
        > /etc/apt/sources.list.d/mssql-release.list \
    && apt-get install -y --no-install-recommends \
        locales \
        apt-transport-https \
    && echo "en_US.UTF-8 UTF-8" > /etc/locale.gen \
    && locale-gen \
    && apt-get update \
    && apt-get -y --no-install-recommends install \
        unixodbc-dev \
        msodbcsql17

RUN docker-php-ext-install mbstring pdo pdo_mysql \
    && pecl install sqlsrv pdo_sqlsrv xdebug \
    && docker-php-ext-enable sqlsrv pdo_sqlsrv xdebug

RUN mkdir /opt/ssl
RUN curl -sSL -o /opt/ssl/rds-combined-ca-bundle.pem https://s3.amazonaws.com/rds-downloads/rds-combined-ca-bundle.pem

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN php -d memory_limit=-1 composer.phar require aws/aws-sdk-php

RUN echo "output_buffering = 1" >> "$PHP_INI_DIR/php.ini"

COPY code/ /var/www/html/
