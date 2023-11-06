#!/bin/bash

SCRIPT=$(readlink -f "$0")
SCRIPTPATH=$(dirname "$SCRIPT")

USER="www-data"

echo $SCRIPTPATH

cd $SCRIPTPATH

echo 'Запус обновлений исходного кода'
git pull
echo 'Обновление исходного кода завершено'
echo 'Запус обновлений пакетов composer'
sudo -u $USER composer update
echo 'Обновление пакетов composer завершено'

/usr/bin/php8.2 artisan schedule:run
