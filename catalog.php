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
        <h2>Каталог</h2>
        
        <div class="catalog-grid">
            <div class="product-card">
                <img src="img/oxfords.jpg" alt="Мужские оксфорды">
                <h3>Оксфорды Classic</h3>
                <p>Элегантная мужская обувь из натуральной кожи.</p>
                <a href="product1.php">Подробное описание товара</a>
            </div>

            <div class="product-card">
                <img src="img/red-velvet.jpg" alt="Женские туфли">
                <h3>Туфли Red Velvet</h3>
                <p>Изящные женские туфли-лодочки на высоком каблуке.</p>
                <a href="product2.php">Подробное описание товара</a>
            </div>

            <div class="product-card">
                <img src="img/runpro.jpg" alt="Спортивные кроссовки">
                <h3>Кроссовки RunPro</h3>
                <p>Легкие и дышащие кроссовки для спорта и жизни.</p>
                <a href="product3.php">Подробное описание товара</a>
            </div>
        </div>
        <hr>
    </main>

    <footer>
        <hr>
        <p>&copy; 2026 Сити-Класс. Все права защищены.</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>