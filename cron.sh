#!/bin/bash

SCRIPT=$(readlink -f "$0")
SCRIPTPATH=$(dirname "$SCRIPT")

echo $SCRIPTPATH

cd $SCRIPTPATH

echo 'Запус обновлений исходного кода'
git pull --rebase
echo 'Обновление исходного кода завершено'
echo 'Запус обновлений пакетов composer'
composer update
echo 'Обновление пакетов composer завершено'

/usr/bin/php8.2 artisan schedule:run
