composer install
chown -R www-data:www-data /var/www/storage
exec "php-fpm"
