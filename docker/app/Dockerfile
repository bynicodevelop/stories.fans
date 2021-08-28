FROM php:8.0-apache

RUN apt update \
    && apt install -y \
    g++ \
    build-essential \
    libicu-dev \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    zlib1g-dev \
    supervisor \
    curl \
    ffmpeg \
    && docker-php-ext-configure \
    gd --with-jpeg \
    && docker-php-ext-configure \
    exif \
    && docker-php-ext-install \
    intl \
    opcache \
    pdo \
    pdo_pgsql \
    pgsql \
    exif \
    gd

RUN pecl install redis \
    && docker-php-ext-enable redis exif

RUN rm -rf /var/www/html \
    && mkdir -p /var/lock/apache2 /var/run/apache2 /var/log/apache2 /var/www/html \
    && chown -R www-data:www-data /var/lock/apache2 /var/run/apache2 /var/log/apache2 /var/www/html

RUN a2dismod mpm_event \
    && a2enmod mpm_prefork \
    && a2enmod rewrite

RUN mv /etc/apache2/apache2.conf /etc/apache2/apache2.conf.dist  \
    && rm /etc/apache2/conf-enabled/* /etc/apache2/sites-enabled/*

COPY /docker/app/apache/apache2.conf /etc/apache2/apache2.conf
COPY /docker/app/sites-enabled/000-default.conf /etc/apache2/sites-enabled/000-default.conf

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY /docker/app/php/php.ini /usr/local/etc/php/
COPY /docker/app/run /usr/local/bin/
COPY /docker/app/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

COPY /www /var/www/html

RUN mkdir -p /var/www/html/storage/app/public \
    /var/www/html/storage/app/private \
    /var/www/html/storage/uploads \
    /var/www/html/storage/framework/cache \
    /var/www/html/storage/framework/sessions \
    /var/www/html/storage/framework/views \
    /var/www/html/storage/logs

WORKDIR /var/www/html

RUN chown -R www-data /var/www/html/bootstrap/cache/
RUN chown -R www-data /var/www/html/public
RUN chown -R www-data /var/www/html/storage
RUN chmod -R 755 /var/www/html/storage

RUN php artisan storage:link

RUN php artisan key:generate --force

EXPOSE 80

CMD /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
