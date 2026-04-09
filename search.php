<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Результаты поиска - Сити-Класс</title>
    <!-- Подключение библиотеки Бутстрап 5 по заданию -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">

    <div class="container mt-5 p-4 bg-white shadow rounded">
        <h2 class="mb-4 text-center border-bottom pb-3" style="color: #2c3e50; border-color: #e67e22 !important;">
            Результаты поиска
        </h2>
        <div class="text-center mb-4">
            <a href="index.php" class="btn btn-outline-secondary">Вернуться на главную страницу</a>
        </div>

        <?php
        // Проверяем, пришли ли данные из формы
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search_q'])) {
            
            // Валидация: очищаем от "мусора" (пробелы, теги)
            $search_q = trim($_POST['search_q']);
            $search_q = htmlspecialchars($search_q);
            
            if (empty($search_q)) {
                echo "<div class='alert alert-danger'>Вы отправили пустой поисковый запрос.</div>";
            } else {
                echo "<h4 class='mb-4'>Вы искали: <strong class='text-warning'>" . $search_q . "</strong></h4>";

                // Подключение к БД в Docker (хост: db, юзер: root, пароль: root, база: site)
                $l = mysqli_connect('db', 'root', 'root', 'site');
                
                if (!$l) {
                    die("<div class='alert alert-danger'>Ошибка подключения к MySQL: " . mysqli_connect_error() . "</div>");
                }

                // Подключение к БД в Docker
                $l = mysqli_connect('db', 'root', 'root', 'site');
                
                if (!$l) {
                    die("<div class='alert alert-danger'>Ошибка подключения к MySQL: " . mysqli_connect_error() . "</div>");
                }

                // ВАЖНО: Принудительно устанавливаем кодировку соединения PHP с MySQL!
                mysqli_set_charset($l, "utf8mb4");

                // Защита от SQL-инъекций
                $safe_q = mysqli_real_escape_string($l, $search_q);

                // Защита от SQL-инъекций
                $safe_q = mysqli_real_escape_string($l, $search_q);
                
                // SQL запрос: ищем совпадения в колонке title_value
                $sql = "SELECT * FROM product WHERE name LIKE '%$safe_q%' OR short_description LIKE '%$safe_q%'";
                $result = mysqli_query($l, $sql);

                // ЗАДАНИЕ 2: Создаем ассоциированный многомерный массив
                $products_array = [];
                
                if (mysqli_num_rows($result) > 0) {
                    // Заполняем массив данными о каждом товаре
                    while($row = mysqli_fetch_assoc($result)) {
                        $products_array[] = $row;
                    }

                    // Выводим данные из МНОГОМЕРНОГО массива средствами Bootstrap 5
                    echo "<div class='row'>";
                    foreach ($products_array as $product) {
                        echo "
                        <div class='col-md-4 mb-3'>
                            <div class='card h-100 border-warning'>
                                <div class='card-body'>
                                    <h5 class='card-title' style='color:#e67e22;'>" . $product['title_value'] . "</h5>
                                    <p class='card-text'>" . $product['content'] . "</p>
                                </div>
                                <div class='card-footer bg-transparent border-warning text-center'>
                                    <a href='catalog.php' class='btn btn-dark w-100'>Перейти в каталог</a>
                                </div>
                            </div>
                        </div>";
                    }
                    echo "</div>";

                } else {
                    echo "<div class='alert alert-warning'>К сожалению, по вашему запросу ничего не найдено.</div>";
                }

                // Сбрасываем запрос и закрываем соединение
                mysqli_free_result($result);
                mysqli_close($l);
            }
        } else {
            echo "<div class='alert alert-info'>Пожалуйста, воспользуйтесь формой поиска на главной странице.</div>";
        }
        ?>
    </div>

    <footer style="margin-top: 50px; padding: 20px 0; background-color: #1a252f; color: #ccc; text-align: center;">
        <p>&copy; 2026 Сити-Класс. Все права защищены.</p>
        <p style="font-size: 12px; margin-top: 10px;">
            <a href="#" onclick="openPrivacyPolicy(); return false;" style="color: #e67e22;">Читать Политику конфиденциальности (Окно)</a> | 
            <a href="privacy.txt" download style="color: #3498db;">Скачать Политику (TXT)</a>
        </p>
    </footer>

    <script src="script.js"></script>
</body>
</html>