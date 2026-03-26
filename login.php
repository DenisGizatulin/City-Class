<?php require_once 'db.php'; 
$reg_message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = $_POST['password'];
    
    // Хэширование пароля (Безопасность)
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name, email, password_hash) VALUES ('$name', '$email', '$password_hash')";
    if (mysqli_query($conn, $sql)) {
        $reg_message = "<p style='color:green;'>Регистрация прошла успешно!</p>";
    } else {
        $reg_message = "<p style='color:red;'>Ошибка (возможно email уже занят).</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Личный кабинет - Сити-Класс</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header><h1>СИТИ-КЛАСС</h1><p>Личный кабинет</p></header>
    <nav>
        <ul>
            <li><a href="index.php">Главная</a></li>
            <li><a href="catalog.php">Каталог</a></li>
            <li><a href="contacts.php">Контакты</a></li>
            <li><a href="login.php">Личный кабинет</a></li>
        </ul>
    </nav>
    <main>
        <div class="auth-grid">
            <div class="auth-box">
                <h3>Вход</h3>
                <form action="#" method="post">
                    <div class="form-group"><label class="title">Email:</label><input type="email" required></div>
                    <div class="form-group"><label class="title">Пароль:</label><input type="password" required></div>
                    <button type="submit">Войти</button>
                </form>
            </div>
            <div class="auth-box">
                <h3>Регистрация</h3>
                <?php echo $reg_message; ?>
                <form action="login.php" method="post">
                    <input type="hidden" name="register" value="1">
                    <div class="form-group"><label class="title">Имя:</label><input type="text" name="name" required></div>
                    <div class="form-group"><label class="title">Email:</label><input type="email" name="email" required></div>
                    <div class="form-group"><label class="title">Пароль:</label><input type="password" name="password" required></div>
                    <button type="submit" style="background-color: #2c3e50;">Зарегистрироваться</button>
                </form>
            </div>
        </div>
    </main>
    <script src="script.js"></script>
</body>
</html>