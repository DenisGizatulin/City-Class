-- Создаем саму базу данных (если её вдруг нет) и задаем ей кодировку
CREATE DATABASE IF NOT EXISTS `site` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Говорим MySQL, что дальше мы работаем именно внутри базы `site`
USE `site`;

-- Принудительно задаем кодировку для текущей сессии
SET NAMES 'utf8mb4';
SET CHARACTER SET utf8mb4;

-- Создаем таблицу
CREATE TABLE IF NOT EXISTS `title` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title_value` varchar(60) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Очищаем таблицу (на случай перезапусков)
TRUNCATE TABLE `title`;

-- Вставляем наши товары
INSERT INTO `title` (`title_value`, `content`) VALUES
('Оксфорды Classic', 'Мужские классические туфли из натуральной кожи. Размеры 40-45. Идеально для офиса.'),
('Туфли Red Velvet', 'Женские замшевые туфли-лодочки на шпильке 10 см. Цвет: бордовый.'),
('Кроссовки RunPro', 'Спортивные беговые кроссовки с пеной EVA для амортизации. Дышащая сетка.');