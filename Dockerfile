FROM php:8.0-apache

RUN apt update \
    && apt install -y \
    g++ \
    build-essential \
    libicu-dev \
    libpq-dev \
    libzip-dev \
    zip \
    zlib1g-dev \
    supervisor \
    curl \
    && docker-php-ext-install \
    intl \
    opcache \
    pdo \
    pdo_pgsql \
    pgsql

RUN curl -fsSL https://deb.nodesource.com/setup_current.x | bash -

RUN apt update \
    && apt install -y \
    nodejs

RUN npm install -g npm@7.20.5

RUN rm -rf /var/www/html \
    && mkdir -p /var/lock/apache2 /var/run/apache2 /var/log/apache2 /var/www/html \
    && chown -R www-data:www-data /var/lock/apache2 /var/run/apache2 /var/log/apache2 /var/www/html

RUN a2dismod mpm_event \
    && a2enmod mpm_prefork \
    && a2enmod rewrite

RUN mv /etc/apache2/apache2.conf /etc/apache2/apache2.conf.dist  \
    && rm /etc/apache2/conf-enabled/* /etc/apache2/sites-enabled/*

COPY docker/apache/apache2.conf /etc/apache2/apache2.conf
COPY docker/sites-enabled/000-default.conf /etc/apache2/sites-enabled/000-default.conf

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY docker/php/php.ini /usr/local/etc/php/
COPY docker/run /usr/local/bin/
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

COPY www /var/www/html

WORKDIR /var/www/html

RUN chown -R www-data /var/www/html/public
RUN chown -R www-data /var/www/html/storage
RUN chmod -R 755 /var/www/html/storage

RUN npm i
RUN npm run prod

RUN composer install
RUN php artisan key:generate

EXPOSE 80

CMD /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
