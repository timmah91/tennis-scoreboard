#!/usr/bin/env bash

# install depends
rm -rf vendor composer.lock
composer install

#create db
touch /var/www/database/database.sqlite

#run migrations and seed
php artisan migrate:reset
php artisan migrate:fresh
php artisan db:seed
