-!/bin/bash

echo "Обновляем Composer"
echo "------------------------------------"
sleep 1s
composer update

echo "Установка зависимостей Composer"
echo "------------------------------------"
sleep 1s
composer install

echo "Установка пакетов npm"
echo "------------------------------------"
sleep 1s
npm install

echo "Сборка проекта"
echo "------------------------------------"
sleep 1s
npm run build

echo "Миграция таблиц в базу данных. Создание Администратора (почта: admin@tyuiu.ru, пароль: 1234567890)"
echo "------------------------------------"
sleep 1s
php artisan migrate --seed