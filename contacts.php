<?php 
require_once 'db.php'; 
$message = "";

// Обработка формы отзывов после нажатия кнопки "Опубликовать отзыв"
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_name'])) {
    
    // Получаем и очищаем данные из формы
    $name = mysqli_real_escape_string($conn, trim($_POST['user_name']));
    $product_alias = mysqli_real_escape_string($conn, trim($_POST['product_id']));
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 5;
    $review_text = mysqli_real_escape_string($conn, trim($_POST['review_text']));
    
    // Обрабатываем наш красивый переключатель (Toggle Switch)
    $recommend = isset($_POST['recommend']) ? 'Да' : 'Нет';
    
    // Добавляем информацию о рекомендации прямо в текст отзыва, 
    // чтобы не менять структуру таблицы в базе данных
    $final_review_text = "Рекомендует друзьям: " . $recommend . ". Текст отзыва: " . $review_text;

    // Проверяем, что обязательные поля не пустые
    if (!empty($name) && !empty($review_text)) {
        // SQL-запрос на вставку данных в таблицу reviews
        $sql = "INSERT INTO reviews (user_name, product_alias, rating, review_text) 
                VALUES ('$name', '$product_alias', '$rating', '$final_review_text')";
        
        if (mysqli_query($conn, $sql)) {
            $message = "<div style='color:green; text-align:center; font-weight:bold; font-size: 18px; margin-bottom: 20px; padding: 10px; border: 2px solid green; border-radius: 5px; background: #e8f5e9;'>Спасибо! Ваш отзыв успешно сохранен!</div>";
        } else {
            $message = "<div style='color:red; text-align:center; margin-bottom: 20px;'>Ошибка добавления в БД: " . mysqli_error($conn) . "</div>";
        }
    } else {
        $message = "<div style='color:red; text-align:center; margin-bottom: 20px;'>Пожалуйста, заполните все обязательные поля!</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Контакты и Отзывы - Сити-Класс</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <h1>СИТИ-КЛАСС</h1>
        <p>Свяжитесь с нами и оставьте свой отзыв!</p>
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
        <!-- ========================================== -->
        <!-- БЛОК 1: КОНТАКТНЫЕ ДАННЫЕ И КАРТА          -->
        <!-- ========================================== -->
        <h2 style="width: 100%;">Наши контакты</h2>
        <ul style="font-size: 16px; line-height: 1.8;">
            <li><strong>Контактный номер телефона:</strong> +7 (495) 123-45-67</li>
            <li><strong>Адрес:</strong> г. Москва, ул. Тверская, д. 15</li>
            <li><strong>Email:</strong> <a href="mailto:info@city-class.ru" style="color: #e67e22; text-decoration: none;">info@city-class.ru</a></li>
        </ul>

        <h3 style="margin-top: 30px;">Мы на карте:</h3>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2244.7735690367035!2d37.605654302530155!3d55.76243554278174!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x46b54a4681f76ab9%3A0xfeacec4c9d1674bf!2z0KLQstC10YDRgdC60LDRjyDRg9C7LiwgMTUsINCc0L7RgdC60LLQsCwg0KDQvtGB0YHQuNGPLCAxMjUwMDk!5e0!3m2!1sru!2sfi!4v1772107884861!5m2!1sru!2sfi" width="100%" height="450" style="border:0; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

        <!-- Разделительная линия между блоками -->
        <hr style="margin: 50px 0; border: 0; height: 2px; background-color: #e67e22; opacity: 0.5;">

        <!-- ========================================== -->
        <!-- БЛОК 2: ГОСТЕВАЯ СТРАНИЦА (ФОРМА ОТЗЫВОВ)  -->
        <!-- ========================================== -->
        <h2 style="text-align: center; width: 100%;">Оставьте отзыв</h2>
        <p style="text-align: center; margin-bottom: 30px;">Пожалуйста, заполните форму ниже, чтобы оставить свой отзыв о нашем магазине.</p>

        <!-- БЛОК ВЫВОДА СООБЩЕНИЯ ОТ PHP -->
        <?php echo $message; ?>

        <div class="form-container">
            <!-- action указывает на этот же файл для обработки -->
            <form action="contacts.php" method="post">
                
                <!-- 1. Однострочное текстовое поле -->
                <div class="form-group">
                    <label class="title" for="name">Ваше имя:</label>
                    <input type="text" id="name" name="user_name" required placeholder="Например: Иван Иванов">
                </div>

                <!-- 2. Раскрывающийся список -->
                <div class="form-group">
                    <label class="title" for="product">О каком товаре отзыв?:</label>
                    <select id="product" name="product_id">
                        <option value="oxfords">Мужские туфли «Оксфорды Classic»</option>
                        <option value="redvelvet">Женские туфли «Red Velvet»</option>
                        <option value="runpro">Спортивные кроссовки «RunPro»</option>
                        <option value="other">Отзыв о магазине в целом</option>
                    </select>
                </div>

                <!-- 3. Радио кнопка -->
                <div class="form-group">
                    <label class="title">Оценка качества:</label>
                    <div class="radio-checkbox-group">
                        <label><input type="radio" name="rating" value="5" checked> Отлично</label>
                        <label><input type="radio" name="rating" value="4"> Хорошо</label>
                        <label><input type="radio" name="rating" value="3"> Удовлетворительно</label>
                    </div>
                </div>

                <!-- 4. Переключатель (Toggle Switch) -->
                <div class="form-group">
                    <label class="title">Рекомендовали бы нас друзьям?:</label>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span style="font-weight: 600;">Нет</span>
                        <label class="switch">
                            <input type="checkbox" name="recommend" checked>
                            <span class="slider"></span>
                        </label>
                        <span style="font-weight: 600; color: #e67e22;">Да!</span>
                    </div>
                </div>

                <!-- 5. Многострочное текстовое поле -->
                <div class="form-group">
                    <label class="title" for="review">Текст отзыва:</label>
                    <textarea id="review" name="review_text" rows="5" required placeholder="Напишите подробный отзыв о товаре или обслуживании..."></textarea>
                </div>

                <!-- 6. Прокручивающееся текстовое поле -->
                <div class="form-group">
                    <label class="title">Правила публикации отзывов:</label>
                    <textarea class="scrollable-text" readonly>
Правила сайта "Сити-Класс":
1. Запрещена ненормативная лексика.
2. Отзыв должен касаться приобретенного товара или работы магазина.
3. Запрещена публикация ссылок на сторонние ресурсы и реклама.
4. Администрация оставляет за собой право удалить отзыв, нарушающий правила.
5. Ваши личные данные (email) не публикуются в открытом доступе.
6. Спасибо, что помогаете нам стать лучше!
                    </textarea>
                </div>

                <!-- 7. Флажок (Checkbox) -->
                <div class="form-group">
                    <div class="radio-checkbox-group">
                        <label style="font-weight: 600; color: #e67e22;">
                            <input type="checkbox" id="agree" name="agree" required>
                            Я прочитал правила
                        </label>
                    </div>
                </div>

                <!-- 8. Кнопка для подтверждения введенных данных -->
                <button type="submit">Опубликовать отзыв</button>

            </form>
        </div>
    </main>

    <footer>
        <p>&copy; 2026 Сити-Класс. Все права защищены.</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>