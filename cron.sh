#!/bin/bash

SCRIPT=$(readlink -f "$0")
SCRIPT_PATH=$(dirname "$SCRIPT")
PHP_PATH="/usr/bin/php8.2"

USER="www-data"

# shellcheck disable=SC2164
cd "$SCRIPT_PATH"

echo "$SCRIPT_PATH"

cp "$SCRIPT_PATH/.env" "$SCRIPT_PATH/.env.backup"
cp "$SCRIPT_PATH/.env.production" "$SCRIPT_PATH/.env"

echo 'Запус обновлений исходного кода'
git pull origin main
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
#$PHP_PATH artisan config:cache
#$PHP_PATH artisan route:cache
#$PHP_PATH artisan storage:link

echo "Запуск выполнения команд по расписанию"
$PHP_PATH artisan schedule:run
