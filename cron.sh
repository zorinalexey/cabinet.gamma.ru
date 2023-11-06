#!/bin/bash

SCRIPT=$(readlink -f "$0")
SCRIPT_PATH=$(dirname "$SCRIPT")
PHP_PATH="/usr/bin/php8.2"

USER="www-data"

cd "$SCRIPT_PATH"

echo "$SCRIPT_PATH"


echo 'Запус обновлений исходного кода'
git pull
echo 'Обновление исходного кода завершено'

echo "$SCRIPT_PATH/composer.json"
sudo chmod 777 "$SCRIPT_PATH/composer.json"
sudo chown -R $USER:$USER "$SCRIPT_PATH"

echo 'Запус обновлений пакетов composer'
sudo -u $USER composer update
echo 'Обновление пакетов composer завершено'

$PHP_PATH artisan migrate
$PHP_PATH artisan config:clear
$PHP_PATH artisan route:clear
$PHP_PATH artisan config:cache
$PHP_PATH artisan route:cache
$PHP_PATH artisan schedule:run
