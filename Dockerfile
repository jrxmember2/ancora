FROM node:22-alpine AS assets
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY tailwind.config.js postcss.config.js ./
COPY src ./src
COPY app ./app
COPY modules ./modules
COPY public ./public
RUN npm run build

FROM php:8.4-apache
RUN apt-get update     && apt-get install -y --no-install-recommends libzip-dev unzip     && docker-php-ext-install pdo pdo_mysql     && a2enmod rewrite headers     && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html
COPY . /var/www/html
COPY --from=assets /app/public/assets/css/app.css /var/www/html/public/assets/css/app.css
COPY docker/apache-vhost.conf /etc/apache2/sites-available/000-default.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh     && chown -R www-data:www-data /var/www/html

EXPOSE 80
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
