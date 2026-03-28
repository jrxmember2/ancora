FROM node:22-alpine AS assets
WORKDIR /app
COPY package*.json ./
RUN if [ -f package-lock.json ]; then npm ci; else npm install; fi
COPY . .
RUN npm run build

FROM php:8.3-apache

RUN apt-get update && apt-get install -y     libpq-dev     unzip     git  && docker-php-ext-install pdo pdo_pgsql  && a2enmod rewrite headers expires  && rm -rf /var/lib/apt/lists/*

COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY docker/apache/apache2.conf /etc/apache2/apache2.conf

WORKDIR /var/www/html
COPY . .
COPY --from=assets /app/public/build /var/www/html/public/build

RUN mkdir -p /var/www/html/storage/logs /var/www/html/storage/sessions /var/www/html/storage/temp /var/www/html/public/assets/uploads  && chown -R www-data:www-data /var/www/html/storage /var/www/html/public/assets/uploads

EXPOSE 80
