CREATE DATABASE IF NOT EXISTS `site` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `site`;

SET NAMES 'utf8mb4';
SET CHARACTER SET utf8mb4;

-- ТАБЛИЦА ТОВАРОВ (image имеет тип LONGTEXT для хранения самих картинок в Base64)
CREATE TABLE IF NOT EXISTS `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `manufacturer_id` smallint(6) NOT NULL DEFAULT 1,
  `name` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `short_description` text NOT NULL,
  `description` text NOT NULL,
  `price` decimal(20,2) NOT NULL,
  `image` LONGTEXT NOT NULL,
  `available` smallint(1) NOT NULL DEFAULT '1',
  `meta_keywords` varchar(255) NOT NULL DEFAULT '',
  `meta_description` varchar(255) NOT NULL DEFAULT '',
  `meta_title` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;  

CREATE TABLE IF NOT EXISTS `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) NOT NULL,
  `product_alias` varchar(255) NOT NULL,
  `rating` int(11) NOT NULL,
  `review_text` text NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

TRUNCATE TABLE `product`;

-- Добавляем 3 товара. Картинки хранятся прямо тут (в виде Base64-заглушки)
INSERT INTO `product` (`name`, `alias`, `short_description`, `description`, `price`, `image`) VALUES
('Оксфорды Classic', 'oxfords', 'Элегантная мужская обувь из натуральной кожи.', 'Эта модель производится вручную на фабрике в Италии. В процессе создания используется технология рантового крепления (Goodyear Welted), которая обеспечивает непревзойденную долговечность и возможность замены подошвы в будущем. Внутренняя отделка выполнена из мягкой телячьей кожи.', 8500.00, 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII='),
('Туфли Red Velvet', 'redvelvet', 'Изящные женские туфли-лодочки на высоком каблуке.', 'Модель «Red Velvet» создана для женщин, ценящих утонченность и элегантность. Удобная анатомическая колодка и мягкая кожаная стелька с супинатором существенно снижают нагрузку на стопу, несмотря на высокий каблук (10 см). Выполнены из натуральной премиальной замши.', 6200.00, 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII='),
('Кроссовки RunPro', 'runpro', 'Легкие и дышащие кроссовки для спорта и жизни.', 'Кроссовки «RunPro» спроектированы с применением новейших спортивных технологий. Верх из технологичной сетки плотно облегает стопу, гарантируя отличную вентиляцию. Промежуточная подошва из легкой пены EVA поглощает ударные нагрузки при беге. Отличный выбор как для фитнеса, так и для прогулок.', 4990.00, 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=');