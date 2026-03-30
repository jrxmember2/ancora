#!/usr/bin/env sh
set -eu

mkdir -p /var/www/html/storage/logs          /var/www/html/storage/sessions          /var/www/html/storage/temp          /var/www/html/public/uploads/propostas          /var/www/html/public/uploads/clientes          /var/www/html/public/uploads/contratos          /var/www/html/public/uploads/branding          /var/www/html/public/assets/uploads/branding

chown -R www-data:www-data /var/www/html/storage /var/www/html/public/uploads /var/www/html/public/assets/uploads
chmod -R 775 /var/www/html/storage /var/www/html/public/uploads /var/www/html/public/assets/uploads

exec apache2-foreground
