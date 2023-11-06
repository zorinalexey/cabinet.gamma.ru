#!/bin/bash

SCRIPT=$(readlink -f "$0")
SCRIPT_PATH=$(dirname "$SCRIPT")
PHP_PATH="/usr/bin/php8.2"

USER="www-data"

cd "$SCRIPT_PATH"

echo "$SCRIPT_PATH"

cp "$SCRIPT_PATH/.env" "$SCRIPT_PATH/.env.backup"

echo 'Запус обновлений исходного кода'
git pull origin main
echo 'Обновление исходного кода завершено'

echo "$SCRIPT_PATH/composer.json"
sudo chmod 777 "$SCRIPT_PATH/composer.json"
sudo chown -R $USER:$USER "$SCRIPT_PATH"

echo 'Запус обновлений пакетов composer'
sudo -u $USER composer update
echo 'Обновление пакетов composer завершено'

#git add .
#git commit -m 'auto-commit'
#git push origin main

$PHP_PATH artisan migrate
$PHP_PATH artisan config:clear
$PHP_PATH artisan route:clear
$PHP_PATH artisan config:cache
$PHP_PATH artisan route:cache
$PHP_PATH artisan storage:link
$PHP_PATH artisan schedule:run
