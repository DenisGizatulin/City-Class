<?php require_once 'db.php'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Каталог - Сити-Класс</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .catalog-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px; margin: 20px 0; }
        .product-card { padding: 15px; background: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: transform 0.2s; display: flex; flex-direction: column;}
        .product-card:hover { transform: translateY(-5px); }
        .product-card img { width: 100%; height: auto; object-fit: cover; aspect-ratio: 1 / 1; border-radius: 8px; }
        .product-card h3 { margin: 15px 0 10px; font-size: 1.2em; }
        .product-card p { color: #555; margin-bottom: 12px; flex-grow: 1;}
        .product-card h4 { color: #e74c3c; font-size: 1.3em; margin: 10px 0; }
        .btn-details { background: #2c3e50; color: white; border: none; padding: 10px; border-radius: 6px; cursor: pointer; transition: background 0.3s; width: 100%; font-weight: bold; margin-bottom: 10px;}
        .btn-details:hover { background: #34495e; }
        
        /* Кнопка добавления в корзину */
        .btn-add-cart { background: #27ae60; color: white; border: none; padding: 10px; border-radius: 6px; cursor: pointer; transition: background 0.3s; width: 100%; font-weight: bold; }
        .btn-add-cart:hover { background: #2ecc71; }
        
        .sort-panel { margin: 20px 0; text-align: right; font-size: 0.9em; }
        .sort-panel a { margin: 0 10px; text-decoration: none; color: #2c3e50; font-weight: 600; padding: 6px 12px; border-radius: 20px; transition: all 0.3s; }
        .sort-panel a:hover, .sort-panel .active { background-color: #e67e22; color: white; }
    </style>
</head>
<body>
    <header><h1>СИТИ-КЛАСС</h1><hr><p>Лучший выбор для ваших ног</p></header>
    <nav>
        <ul>
            <li><a href="index.php">Главная</a></li>
            <li><a href="catalog.php">Каталог</a></li>
            <li><a href="contacts.php">Контакты</a></li>
            <li><a href="login.php">Личный кабинет</a></li>
            <li><button id="theme-toggle" class="theme-btn">🌙 Тёмная тема</button></li>
        </ul>
    </nav>
    <main>
        <hr><h2>Каталог товаров</h2>
        <div class="sort-panel">
            Сортировать: 
            <a href="?sort=name_asc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'name_asc') ? 'class="active"' : '' ?>>А-Я</a>
            <a href="?sort=price_asc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'price_asc') ? 'class="active"' : '' ?>>Цена (возр.)</a>
            <a href="?sort=price_desc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'price_desc') ? 'class="active"' : '' ?>>Цена (убыв.)</a>
        </div>
        
        <div class="catalog-grid">
            <?php
            $order_by = "id DESC"; 
            if (isset($_GET['sort'])) {
                if($_GET['sort'] == 'name_asc') $order_by = "name ASC";
                if($_GET['sort'] == 'price_asc') $order_by = "price ASC";
                if($_GET['sort'] == 'price_desc') $order_by = "price DESC";
            }
            $result = mysqli_query($conn, "SELECT * FROM product ORDER BY $order_by");
            
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $formatted_price = number_format($row['price'], 0, ',', ' ') . " руб.";
                    $available = $row['available'];
                    $disabled = ($available <= 0) ? 'disabled' : '';
                    $btnText = ($available > 0) ? 'В КОРЗИНУ 🛒' : 'НЕТ В НАЛИЧИИ';
                    echo "
                    <div class='product-card card'>
                        <div class='card__image'>
                            <img src='{$row['image']}' alt='{$row['name']}'>
                        </div>
                        <h3 class='card__title'>{$row['name']}</h3>
                        <p>{$row['short_description']}</p>
                        
                        <h4 class='card__price--common'>{$formatted_price}</h4>
                        <!-- Скрытые поля -->
                        <span class='card__price--discount' style='display:none;'>{$formatted_price}</span>
                        <input type='hidden' class='card__available' value='{$available}'>
                        
                        <button type='button' class='btn-details' onclick=\"window.location.href='product.php?id={$row['id']}'\">ПОДРОБНЕЕ →</button>
                        <button type='button' class='btn-add-cart card__add' {$disabled}>{$btnText}</button>
                    </div>";
                }
            } else {
                echo "<p style='text-align:center; grid-column:1/-1;'>Товаров пока нет.</p>";
            }
            ?>
        </div>
    </main>

    <!-- КНОПКА И ОКНО КОРЗИНЫ -->
    <button class="cart" id="cart" title="Корзина"><span style="font-size: 24px;">🛒</span><div class="cart__num" id="cart_num">0</div></button>
    <div class="popup"><div class="popup__container" id="popup_container"><button class="popup__close" id="popup_close">✖</button><div class="popup__item"><h1 class="popup__title">Оформление заказа</h1></div><div class="popup__item" id="popup_product_list"></div><div class="popup__item"><div class="popup__cost"><h2 class="popup__cost-title">Итого к оплате:</h2><output class="popup__cost-value" id="popup_cost_discount">0</output></div><button style="width: 100%; margin-top: 15px; padding: 15px; background: #27ae60; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; font-size: 18px;">ОПЛАТИТЬ ЗАКАЗ</button></div></div></div>

    <footer style="position: relative;">
        <p>&copy; 2026 Сити-Класс. Все права защищены.</p>
        <p style="font-size: 12px; margin-top: 10px;">
            <a href="#" onclick="openPrivacyPolicy(); return false;" style="color: #e67e22;">Читать Политику конфиденциальности (Окно)</a> | 
            <a href="privacy.txt" download style="color: #3498db;">Скачать Политику (TXT)</a>
        </p>
        <a href="admin.php" style="position: absolute; right: 20px; bottom: 20px; color: #555; text-decoration: none; font-size: 14px; opacity: 0.5;">⚙️ Админ</a>
    </footer>
    <script src="script.js"></script>
</body>
</html>