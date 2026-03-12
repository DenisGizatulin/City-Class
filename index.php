<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Сити-Класс - Интернет-магазин обуви</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <h1>СИТИ-КЛАСС</h1>
        <hr>
        <p>Добро пожаловать в мир стильной и комфортной обуви!</p>
        
        <!-- ФОРМА ПОИСКА С ВАЛИДАЦИЕЙ -->
        <form name="f1" method="post" action="search.php" onsubmit="return validateSearch()" style="margin-top: 15px;">
            <input type="search" name="search_q" id="searchBox" placeholder="Поиск товара..." style="padding: 8px; width: 250px; border-radius: 4px; border: none; font-family: inherit;">
            <input type="submit" value="Поиск" style="padding: 8px 15px; background-color: #e67e22; color: white; border: none; border-radius: 4px; cursor: pointer; font-family: inherit; font-weight: bold;">
        </form>

        <script>
            // JS валидация формы (Задание со звездочкой)
            function validateSearch() {
                var query = document.getElementById("searchBox").value;
                if (query.trim() === "") {
                    alert("Поле поиска не может быть пустым. Введите хотя бы одну букву!");
                    return false;
                }
                return true;
            }
        </script>
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
        <section>
            <h2>О нас</h2>
            <p>Добро пожаловать в интернет-магазин обуви <strong>«Сити-Класс»</strong>! Мы специализируемся на продаже высококачественной обуви для мужчин, женщин и детей. Наша миссия — предоставить каждому клиенту идеальную пару, которая подчеркнет индивидуальность и обеспечит комфорт на весь день.</p>
        </section>

        <section>
            <h2>История фирмы</h2>
            <p>Компания «Сити-Класс» была основана в 2010 году. Начав с небольшого бутика в центре города, мы быстро завоевали доверие покупателей благодаря вниманию к деталям и работе только с проверенными европейскими и мировыми брендами. В 2015 году мы открыли онлайн-продажи, и теперь наша обувь доступна по всей стране.</p>
        </section>

        <section>
            <h2>Наши сотрудники</h2>
            <ul>
                <li><strong>Мазнева Вероника</strong> — Генеральный директор. Стратег и вдохновитель компании.</li>
                <li><strong>Бектенова Жанна</strong> — Главный байер. Человек, который знает все о последних трендах в мире обуви.</li>
                <li><strong>Гизатулин Денис</strong> — Руководитель отдела заботы о клиентах. Гарантирует, что ваша покупка пройдет идеально.</li>
            </ul>
        </section>
        <hr>
    </main>

    <footer>
        <hr>
        <p>&copy; 2026 Сити-Класс. Все права защищены.</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>