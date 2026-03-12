<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Личный кабинет - Сити-Класс</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <h1>СИТИ-КЛАСС</h1>
        <hr>
        <p>Личный кабинет покупателя</p>
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
        <div style="display: flex; justify-content: space-around; flex-wrap: wrap; gap: 20px;">
            
            <div style="width: 45%; min-width: 300px;">
                <h2>Авторизация</h2>
                <form action="#" method="post">
                    <label for="login_email">Логин (Email):</label>
                    <input type="email" id="login_email" name="email" required>
                    
                    <label for="login_password">Пароль:</label>
                    <input type="password" id="login_password" name="password" required>
                    
                    <button type="submit">Войти</button>
                </form>
            </div>

            <div style="width: 45%; min-width: 300px;">
                <h2>Регистрация</h2>
                <form action="#" method="post">
                    <label for="reg_name">Ваше имя:</label>
                    <input type="text" id="reg_name" name="name" required>

                    <label for="reg_email">Email:</label>
                    <input type="email" id="reg_email" name="email" required>
                    
                    <label for="reg_password">Придумайте пароль:</label>
                    <input type="password" id="reg_password" name="password" required>
                    
                    <button type="submit" style="background-color: #2c3e50;">Зарегистрироваться</button>
                </form>
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