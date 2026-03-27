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
                    <li><strong>Наличие:</strong> <?= $product['available'] ? '<span style="color:green;">В наличии на складе</span>' : '<span style="color:red;">Нет в наличии</span>' ?></li>
                </ul>

                <button style="width:100%; padding:15px; background-color:#27ae60; color:white; border:none; border-radius:5px; cursor:pointer; font-size:18px; font-weight:bold; margin-top:20px; transition: 0.3s;">
                    🛒 ДОБАВИТЬ В КОРЗИНУ
                </button>
            </div>
        </div>

        <hr style="margin: 40px 0;">

        <!-- ПОДРОБНОЕ ОПИСАНИЕ ИЗ БАЗЫ ДАННЫХ -->
        <h3>Подробное описание товара</h3>
        <!-- Функция nl2br сохраняет абзацы, введенные в админке -->
        <p class="detailed-desc"><?= nl2br(htmlspecialchars($product['description'])) ?></p>

        <h3>* Особенности ухода</h3>
        <ol style="color: #484343; line-height: 1.8; margin-bottom: 30px;">
            <li>После каждой носки очищайте обувь сухой щеткой.</li>
            <li>Используйте формодержатели для сохранения формы.</li>
            <li>Сушить только естественным путем вдали от батарей.</li>
        </ol>

    </main>

    <footer style="position: relative;">
        <p>&copy; 2026 Сити-Класс. Все права защищены.</p>
        <a href="admin.php" title="Панель администратора" style="position: absolute; right: 20px; bottom: 20px; color: #555; text-decoration: none; font-size: 14px; opacity: 0.5; transition: opacity 0.3s;">⚙️ Админ</a>
    </footer>

    <script src="script.js"></script>
</body>
</html>