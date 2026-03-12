# Используем официальный образ PHP-FPM (FastCGI Process Manager)
FROM php:8.2-fpm

# Устанавливаем расширение для работы с базой данных MySQL
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Рабочая директория
WORKDIR /var/www/html

# Копируем код сайта
COPY . .

# Даем права
RUN chown -R www-data:www-data /var/www/html