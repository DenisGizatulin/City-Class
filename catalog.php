<?php require_once 'db.php'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Каталог - Сити-Класс</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Добавляем промежутки между товарами */
        .catalog-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px; /* Расстояние между карточками */
            margin: 20px 0;
        }

        /* Адаптивность изображений внутри карточек */
        .product-card img {
            width: 100%;
            height: auto;
            object-fit: cover;
            aspect-ratio: 1 / 1; /* Сохраняем квадратную форму */
            border-radius: 8px;
        }

        /* Небольшой отступ для контента внутри карточки */
        .product-card {
            padding: 15px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        /* Заголовок и текст внутри карточки */
        .product-card h3 {
            margin: 15px 0 10px;
            font-size: 1.2em;
        }

        .product-card p {
            color: #555;
            margin-bottom: 12px;
        }

        .product-card h4 {
            color: #e74c3c;
            font-size: 1.3em;
            margin: 10px 0;
        }

        .btn-details {
            background: #2c3e50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s;
            width: 100%;
            font-weight: bold;
        }

        .btn-details:hover {
            background: #e67e22;
        }

        /* Сохраняем исходную стилизацию шапки и футера (если нужно) */
        footer {
            margin-top: 40px;
        }
    </style>
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
        <h2>Каталог товаров</h2>
        
        <div class="catalog-grid">
            <?php
            $result = mysqli_query($conn, "SELECT * FROM product ORDER BY id DESC");
            
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "
                    <div class='product-card'>
                        <img src='{$row['image']}' 
                             width='400' 
                             height='400' 
                             loading='lazy'
                             alt='{$row['name']}'>
                        <h3>{$row['name']}</h3>
                        <p>{$row['short_description']}</p>
                        <h4>" . number_format($row['price'], 0, ',', ' ') . " руб.</h4>
                        
                        <button type='button' class='btn-details' onclick=\"window.location.href='product.php?id={$row['id']}'\">
                            ПОДРОБНЕЕ →
                        </button>
                    </div>";
                }
            } else {
                echo "<p style='text-align:center; grid-column:1/-1; font-size:1.2em; color:#7f8c8d;'>Товаров пока нет. Добавьте их в админ-панели!</p>";
            }
            ?>
        </div>
    </main>

    <footer style="position: relative;">
        <p>&copy; 2026 Сити-Класс. Все права защищены.</p>
        <a href="admin.php" title="Панель администратора" style="position: absolute; right: 20px; bottom: 20px; color: #555; text-decoration: none; font-size: 14px; opacity: 0.5; transition: opacity 0.3s;">⚙️ Админ</a>
    </footer>

    <script src="script.js"></script>
</body>
</html>