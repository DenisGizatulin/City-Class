<?php 
require_once 'db.php'; 

// Получаем ID товара из ссылки
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT * FROM product WHERE id = $id";
$result = mysqli_query($conn, $sql);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    die("<h2 style='text-align:center; padding: 50px;'>Товар не найден! <br><a href='catalog.php' style='color:#e67e22;'>Вернуться в каталог</a></h2>");
}

// "Умное" определение типа таблицы размеров по названию
$name_lower = mb_strtolower($product['name']);
$table_type = 'mens'; 
if (strpos($name_lower, 'кроссовки') !== false || strpos($name_lower, 'спорт') !== false) $table_type = 'sport';
elseif (strpos($name_lower, 'женск') !== false || strpos($name_lower, 'туфли red') !== false) $table_type = 'womens';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($product['name']) ?> - Сити-Класс</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="product-page">

    <header>
        <h1>СИТИ-КЛАСС</h1>
        <p>Карточка товара</p>
    </header>

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
        <h2><?= htmlspecialchars($product['name']) ?></h2>
        
        <div style="display:flex; gap:30px; flex-wrap:wrap; margin-top:20px;">
            <!-- ОГРОМНОЕ ФОТО (Считывается напрямую из БД Base64) -->
            <div class="product-image-container" style="flex:1; min-width:300px; text-align: center;">
                <a href="<?= $product['image'] ?>" target="_blank" title="Увеличить">
                    <img src="<?= $product['image'] ?>" alt="<?= htmlspecialchars($product['name']) ?>" style="width:100%; max-width:500px; border-radius:8px; box-shadow:0 4px 15px rgba(0,0,0,0.1);">
                </a>
                <p style="font-size: 13px; color: #7f8c8d; margin-top: 10px;"><em>* Нажмите на изображение, чтобы открыть его в полном размере.</em></p>
            </div>
            
            <div style="flex:1; min-width:300px;">
                <h1 style="color:#e67e22; margin-top:0; font-size: 2.5em;"><?= number_format($product['price'], 0, ',', ' ') ?> руб.</h1>
                
                <h3>Краткое описание</h3>
                <p class="short-desc"><?= htmlspecialchars($product['short_description']) ?></p>

                <h3>Характеристики</h3>
                <ul class="char-list">
                    <li><strong>Артикул в базе (ID):</strong> <?= $product['id'] ?></li>
                    <li><strong>Алиас (url):</strong> <?= htmlspecialchars($product['alias']) ?></li>
                    <li><strong>Наличие:</strong> 
                        <?php if ($product['available'] > 0): ?>
                            <span style="color:green;"><?= $product['available'] ?> шт. в наличии</span>
                        <?php else: ?>
                            <span style="color:red;">Нет в наличии</span>
                        <?php endif; ?>
                    </li>
                </ul>

                <!-- БЛОК ДЛЯ ДОБАВЛЕНИЯ В КОРЗИНУ (аналогично каталогу) -->
                <div class="card" style="background-color: #fcfcfc; padding: 20px; border-left: 4px solid #27ae60; margin: 30px 0; border-radius: 4px; display: flex; align-items: center; justify-content: space-between;">
                    <!-- Скрытые поля для передачи данных в JS Корзину -->
                    <div class="card__image" style="display:none;"><img src="<?= $product['image'] ?>" alt="<?= htmlspecialchars($product['name']) ?>"></div>
                    <div class="card__title" style="display:none;"><?= htmlspecialchars($product['name']) ?></div>
                    <div class="card__price--discount" style="display:none;"><?= number_format($product['price'], 0, ',', ' ') ?> руб.</div>
                    <!-- Скрытое поле с доступным количеством -->
                    <input type="hidden" class="card__available" value="<?= $product['available'] ?>">
                    
                    <div>
                        <span style="font-size: 1.2em; color: #2c3e50; font-weight: 600;">Цена:</span>
                        <span class="card__price--common" style="font-size: 2em; color: #27ae60; font-weight: bold; margin-left: 15px;"><?= number_format($product['price'], 0, ',', ' ') ?> руб.</span>
                    </div>
                    
                    <!-- Кнопка добавления в корзину (активна только если товар в наличии) -->
                    <?php if ($product['available'] > 0): ?>
                        <button class="card__add" style="padding: 12px 30px; background-color: #27ae60; color: white; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; font-size: 16px; transition: background 0.3s;">В корзину 🛒</button>
                    <?php else: ?>
                        <button disabled style="padding: 12px 30px; background-color: #ccc; color: #666; border: none; border-radius: 5px; font-weight: bold; font-size: 16px;">Нет в наличии</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <hr style="margin: 40px 0;">

        <!-- ПОДРОБНОЕ ОПИСАНИЕ ИЗ БАЗЫ ДАННЫХ -->
        <h3>Подробное описание товара</h3>
        <p class="detailed-desc"><?= nl2br(htmlspecialchars($product['description'])) ?></p>

        <h3>* Особенности ухода</h3>
        <ol style="color: #484343; line-height: 1.8; margin-bottom: 30px;">
            <li>После каждой носки очищайте обувь сухой щеткой.</li>
            <li>Используйте формодержатели для сохранения формы.</li>
            <li>Сушить только естественным путем вдали от батарей.</li>
        </ol>
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
        <a href="admin.php" title="Панель администратора" style="position: absolute; right: 20px; bottom: 20px; color: #555; text-decoration: none; font-size: 14px; opacity: 0.5; transition: opacity 0.3s;">⚙️ Админ</a>
    </footer>

    <script src="script.js"></script>
</body>
</html>