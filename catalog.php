<?php require_once 'db.php'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Каталог - Сити-Класс</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>СИТИ-КЛАСС</h1>
        <hr>
        <p>Лучший выбор для ваших ног</p>
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
        <hr>
        <h2>Каталог товаров (Вывод из БД)</h2>
        
        <div class="catalog-grid">
            <?php
            $result = mysqli_query($conn, "SELECT * FROM product ORDER BY id DESC");
            
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    // Переход по кнопке на единый шаблон product.php с передачей ID
                    echo "
                    <div class='product-card'>
                        <img src='{$row['image']}' alt='{$row['name']}'>
                        <h3>{$row['name']}</h3>
                        <p>{$row['short_description']}</p>
                        <h4>" . number_format($row['price'], 0, ',', ' ') . " руб.</h4>
                        
                        <button type='button' class='btn-details' onclick=\"window.location.href='product.php?id={$row['id']}'\">
                            ПОДРОБНЕЕ
                        </button>
                    </div>";
                }
            } else {
                echo "<p>Товаров нет. Добавьте их в админ-панели!</p>";
            }
            ?>
        </div>
        <hr>
    </main>

    <footer style="position: relative;">
        <p>&copy; 2026 Сити-Класс. Все права защищены.</p>
        <a href="admin.php" title="Панель администратора" style="position: absolute; right: 20px; bottom: 20px; color: #555; text-decoration: none; font-size: 14px; opacity: 0.5; transition: opacity 0.3s;">⚙️ Админ</a>
    </footer>

    <script src="script.js"></script>
</body>
</html>