FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    git \
 && docker-php-ext-install pdo pdo_pgsql \
 && a2enmod rewrite headers expires \
 && rm -rf /var/lib/apt/lists/*

COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html
COPY . .

RUN mkdir -p /var/www/html/public/build \
 && cat \
    /var/www/html/public/assets/css/base.css \
    /var/www/html/public/assets/css/components.css \
    /var/www/html/public/assets/css/pages.css \
    /var/www/html/public/assets/css/modern-overrides.css \
    > /var/www/html/public/build/app.css \
 && mkdir -p /var/www/html/storage/logs /var/www/html/storage/sessions /var/www/html/storage/temp /var/www/html/public/assets/uploads \
 && chown -R www-data:www-data /var/www/html/storage /var/www/html/public/assets/uploads /var/www/html/public/build

EXPOSE 80
