FROM php:7.0-apache

RUN apt-get update && \
    apt-get install -y --no-install-recommends wkhtmltopdf wget libxml2-dev zlib1g-dev git zip unzip libfreetype6-dev libjpeg62-turbo-dev && \
    docker-php-ext-install pdo && \
    docker-php-ext-configure opcache --enable-opcache && \
    docker-php-ext-install opcache && \
    docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ && \
    docker-php-ext-install -j$(nproc) gd && \
    apt-get clean && \
    curl --silent --show-error -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN wget https://github.com/wkhtmltopdf/wkhtmltopdf/releases/download/0.12.4/wkhtmltox-0.12.4_linux-generic-amd64.tar.xz && \
    tar xvf wkhtmltox-0.12.4_linux-generic-amd64.tar.xz && \
    mv wkhtmltox/bin/wkhtmlto* /usr/bin/ && \
    ln -nfs /usr/bin/wkhtmltopdf /usr/local/bin/wkhtmltopdf

COPY docker/config/opcache.ini $PHP_INI_DIR/conf.d/
RUN a2enmod rewrite

COPY . /var/www/html/
VOLUME /var/www/html/upload
RUN php docker/db.php

RUN cd /var/www/html && \
    chmod -R 777 logs/ && \
    chmod -R 777 upload/ && \
    cp -f docker/.htaccess . && \
    cp -f docker/settings.php src/ && \
    composer install --no-interaction --no-dev --optimize-autoloader && \
    composer dump-autoload --optimize --no-dev --classmap-authoritative