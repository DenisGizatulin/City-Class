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
        <!-- ======================================= -->
        <!-- ЛР 4: СЛАЙДЕР НА ГЛАВНОЙ СТРАНИЦЕ       -->
        <!-- ======================================= -->
        <div class="slider">
            <div class="item">
                <img src="https://dummyimage.com/1200x400/2c3e50/ffffff&text=Новая+коллекция+обуви" alt="Слайд 1">
            </div>
            <div class="item">
                <img src="https://dummyimage.com/1200x400/e67e22/ffffff&text=Скидки+до+50%25" alt="Слайд 2">
            </div>
            <div class="item">
                <img src="https://dummyimage.com/1200x400/27ae60/ffffff&text=Спортивные+кроссовки" alt="Слайд 3">
            </div>
            <a class="previous" onclick="previousSlide()">&#10094;</a>
            <a class="next" onclick="nextSlide()">&#10095;</a>
        </div>

        <section>
            <h2>О нас</h2>
            <p>Добро пожаловать в интернет-магазин обуви <strong>«Сити-Класс»</strong>! Мы специализируемся на продаже высококачественной обуви для мужчин, женщин и детей. Наша миссия — предоставить каждому клиенту идеальную пару, которая подчеркнет индивидуальность и обеспечит комфорт на весь день.</p>
        </section>

        <section>
            <h2>История фирмы</h2>
            <p>Компания «Сити-Класс» была основана в 2010 году. Начав с небольшого бутика в центре города, мы быстро завоевали доверие покупателей благодаря вниманию к деталям и работе только с проверенными европейскими и мировыми брендами. В 2015 году мы открыли онлайн-продажи, и теперь наша обувь доступна по всей стране.</p>
        </section>
        <hr>
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

    <script src="script.js?v=2"></script>
</body>
</html>