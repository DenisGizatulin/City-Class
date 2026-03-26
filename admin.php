<?php 
session_start();
$admin_password = 'admin'; 

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login_admin'])) {
    if ($_POST['password'] === $admin_password) $_SESSION['is_admin'] = true;
    else $error_msg = "<p style='color:red; text-align:center;'>Неверный пароль!</p>";
}

if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
    require_once 'db.php'; 
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Управление товарами - Сити-Класс</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header><h1>АДМИН-ПАНЕЛЬ</h1></header>
    <nav>
        <ul>
            <li><a href="index.php">Главная</a></li>
            <li><a href="catalog.php">Каталог</a></li>
            <?php if (isset($_SESSION['is_admin'])): ?>
                <li><a href="admin.php?logout=true" style="background-color: #c0392b;">Выйти</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <main>
        <?php if (!isset($_SESSION['is_admin'])): ?>
            <h2 style="text-align: center; width: 100%;">Вход</h2>
            <?php if(isset($error_msg)) echo $error_msg; ?>
            <div class="form-container" style="max-width: 400px; margin-top: 30px;">
                <form action="admin.php" method="post">
                    <input type="hidden" name="login_admin" value="1">
                    <div class="form-group"><input type="password" name="password" required placeholder="Пароль: admin"></div>
                    <button type="submit">Войти</button>
                </form>
            </div>
        <?php else: ?>
            
            <h2>Добавление товара (Всё хранится в БД)</h2>
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
                $name = mysqli_real_escape_string($conn, trim($_POST['name']));
                $alias = mysqli_real_escape_string($conn, trim($_POST['alias'])); // ИСПРАВЛЕНО: Алиас считывается
                $price = floatval($_POST['price']);
                $short_desc = mysqli_real_escape_string($conn, trim($_POST['short_desc']));
                $desc = mysqli_real_escape_string($conn, trim($_POST['desc'])); // Подробное описание
                
                $base64Image = "";

                if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                    $fileTmp = $_FILES['image']['tmp_name'];
                    $imageString = file_get_contents($fileTmp);
                    $sourceImage = imagecreatefromstring($imageString);
                    
                    if ($sourceImage !== false) {
                        $width = imagesx($sourceImage);
                        $height = imagesy($sourceImage);
                        $newSize = 500; 

                        $minSize = min($width, $height);
                        $cropStartX = ($width - $minSize) / 2;
                        $cropStartY = ($height - $minSize) / 2;

                        $newImage = imagecreatetruecolor($newSize, $newSize);
                        imagecopyresampled($newImage, $sourceImage, 0, 0, $cropStartX, $cropStartY, $newSize, $newSize, $minSize, $minSize);
                        
                        ob_start();
                        imagejpeg($newImage, null, 85);
                        $imageData = ob_get_contents();
                        ob_end_clean();
                        
                        $base64Image = 'data:image/jpeg;base64,' . base64_encode($imageData);
                        
                        imagedestroy($sourceImage);
                        imagedestroy($newImage);
                    }
                }

                if (!empty($name) && !empty($price) && !empty($base64Image)) {
                    // ИСПРАВЛЕНО: Подставляем $alias и $desc в SQL запрос
                    $sql = "INSERT INTO product (name, alias, price, short_description, description, image) 
                            VALUES ('$name', '$alias', '$price', '$short_desc', '$desc', '$base64Image')";
                    if (mysqli_query($conn, $sql)) {
                        echo "<p style='color:green; font-weight:bold; text-align:center;'>Товар успешно добавлен! Изображение переведено в Base64.</p>";
                    } else {
                        echo "<p style='color:red;'>Ошибка: " . mysqli_error($conn) . "</p>";
                    }
                } else {
                    echo "<p style='color:red;'>Заполните все поля и прикрепите картинку!</p>";
                }
            }
            ?>

            <div class="form-container">
                <form action="admin.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="add_product" value="1">
                    <div class="form-group"><label class="title">Название:</label><input type="text" name="name" required></div>
                    <div class="form-group"><label class="title">Алиас (url-имя):</label><input type="text" name="alias" required placeholder="primer-tovara"></div>
                    <div class="form-group"><label class="title">Цена (руб):</label><input type="number" step="0.01" name="price" required></div>
                    <div class="form-group"><label class="title">Фото товара (загружается в БД):</label><input type="file" name="image" accept="image/*" required style="padding-top: 10px;"></div>
                    <div class="form-group"><label class="title">Краткое описание (для каталога):</label><textarea name="short_desc" rows="2" required></textarea></div>
                    <div class="form-group"><label class="title">ПОДРОБНОЕ описание (на страницу товара):</label><textarea name="desc" rows="6" required></textarea></div>
                    <button type="submit">Добавить товар в базу</button>
                </form>
            </div>
            
            <hr>
            <h2>Список товаров</h2>
            <div class="table-container">
                <table>
                    <tr><th>ID</th><th>Название</th><th>Цена</th><th>Алиас</th></tr>
                    <?php
                    $result = mysqli_query($conn, "SELECT * FROM product ORDER BY id DESC");
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr><td>{$row['id']}</td><td>{$row['name']}</td><td>{$row['price']} руб.</td><td>{$row['alias']}</td></tr>";
                    }
                    ?>
                </table>
            </div>
        <?php endif; ?>
    </main>
    <script src="script.js"></script>
</body>
</html>