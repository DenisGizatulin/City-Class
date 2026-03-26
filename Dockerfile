FROM php:8.2-fpm

# Устанавливаем библиотеки для работы с изображениями (GD) и БД
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd mysqli

WORKDIR /var/www/html
COPY . .
RUN chown -R www-data:www-data /var/www/html