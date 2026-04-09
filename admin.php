<?php 
session_start();
$admin_password = 'admin'; // Пароль для входа

// Выход из админки
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit;
}

// Обработка авторизации
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login_admin'])) {
    if ($_POST['password'] === $admin_password) {
        $_SESSION['is_admin'] = true;
        header("Location: admin.php"); // Перезагружаем страницу без POST данных
        exit;
    } else {
        $error_msg = "<p style='color:red; text-align:center;'>Неверный пароль!</p>";
    }
}

// Если авторизован - подключаем БД
if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
    require_once 'db.php'; 

    // Функция для обработки и обрезки изображения (ВЫСОКОЕ КАЧЕСТВО)
    function processImageBase64($fileArray) {
        if(isset($fileArray) && $fileArray['error'] == 0) {
            $fileTmp = $fileArray['tmp_name'];
            
            // Получаем информацию о типе файла, чтобы правильно его загрузить
            $imageInfo = getimagesize($fileTmp);
            if (!$imageInfo) return false; // Если это вообще не картинка
            $mimeType = $imageInfo['mime'];

            // Загружаем картинку в зависимости от её реального формата
            switch ($mimeType) {
                case 'image/jpeg': $sourceImage = @imagecreatefromjpeg($fileTmp); break;
                case 'image/png':  $sourceImage = @imagecreatefrompng($fileTmp); break;
                case 'image/webp': $sourceImage = @imagecreatefromwebp($fileTmp); break;
                default:
                    $imageString = file_get_contents($fileTmp);
                    $sourceImage = @imagecreatefromstring($imageString);
            }
            
            if ($sourceImage !== false) {
                $width = imagesx($sourceImage);
                $height = imagesy($sourceImage);
                
                $newSize = 800; 

                $minSize = min($width, $height);
                $cropStartX = intval(($width - $minSize) / 2);
                $cropStartY = intval(($height - $minSize) / 2);

                $newImage = imagecreatetruecolor($newSize, $newSize);
                
                if ($mimeType == 'image/png') {
                    imagealphablending($newImage, false);
                    imagesavealpha($newImage, true);
                    $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
                    imagefilledrectangle($newImage, 0, 0, $newSize, $newSize, $transparent);
                } else {
                    $white = imagecolorallocate($newImage, 255, 255, 255);
                    imagefill($newImage, 0, 0, $white);
                }

                imageantialias($newImage, true);
                imagecopyresampled($newImage, $sourceImage, 0, 0, $cropStartX, $cropStartY, $newSize, $newSize, $minSize, $minSize);
                
                ob_start();
                if ($mimeType == 'image/png') {
                    imagepng($newImage, null, 0);
                    $imageData = ob_get_contents();
                    $base64Image = 'data:image/png;base64,' . base64_encode($imageData);
                } else {
                    imagejpeg($newImage, null, 100);
                    $imageData = ob_get_contents();
                    $base64Image = 'data:image/jpeg;base64,' . base64_encode($imageData);
                }
                ob_end_clean();
                
                imagedestroy($sourceImage);
                imagedestroy($newImage);

                return $base64Image;
            }
        }
        return false;
    }

    // =======================================================
    // 1. ОБРАБОТКА ДОБАВЛЕНИЯ ТОВАРА (с валидацией)
    // =======================================================
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
        $errors = [];
        
        $name = trim($_POST['name']);
        $alias = trim($_POST['alias']);
        $price = floatval($_POST['price']);
        $available = intval($_POST['available']);
        $short_desc = trim($_POST['short_desc']);
        $desc = trim($_POST['desc']);
        
        // Проверка обязательных полей
        if (empty($name)) $errors[] = "Название товара не может быть пустым.";
        if (empty($alias)) $errors[] = "Алиас (url-имя) не может быть пустым.";
        if ($price <= 0) $errors[] = "Цена должна быть положительным числом.";
        if ($available < 0) $errors[] = "Количество не может быть отрицательным.";
        if (empty($short_desc)) $errors[] = "Краткое описание обязательно.";
        if (empty($desc)) $errors[] = "Подробное описание обязательно.";
        
        // Проверка изображения
        if (!isset($_FILES['image']) || $_FILES['image']['error'] != 0) {
            $errors[] = "Фото товара обязательно для загрузки.";
        } else {
            $base64Image = processImageBase64($_FILES['image']);
            if (!$base64Image) {
                $errors[] = "Некорректный файл изображения. Поддерживаются JPEG, PNG, WEBP.";
            }
        }

        if (empty($errors)) {
            $name = mysqli_real_escape_string($conn, $name);
            $alias = mysqli_real_escape_string($conn, $alias);
            $short_desc = mysqli_real_escape_string($conn, $short_desc);
            $desc = mysqli_real_escape_string($conn, $desc);
            
            $sql = "INSERT INTO product (name, alias, price, available, short_description, description, image) 
                    VALUES ('$name', '$alias', '$price', '$available', '$short_desc', '$desc', '$base64Image')";
            if (mysqli_query($conn, $sql)) {
                $success_msg = "Товар успешно добавлен!";
            } else {
                $db_error = mysqli_error($conn);
            }
        } else {
            $db_error = implode("<br>", $errors);
        }
    }

    // =======================================================
    // 2. ОБРАБОТКА РЕДАКТИРОВАНИЯ ТОВАРА (с валидацией)
    // =======================================================
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_product'])) {
        $id = intval($_POST['product_id']);
        $errors = [];
        
        $name = trim($_POST['name']);
        $alias = trim($_POST['alias']);
        $price = floatval($_POST['price']);
        $available = intval($_POST['available']);
        $short_desc = trim($_POST['short_desc']);
        $desc = trim($_POST['desc']);
        
        if (empty($name)) $errors[] = "Название товара не может быть пустым.";
        if (empty($alias)) $errors[] = "Алиас (url-имя) не может быть пустым.";
        if ($price <= 0) $errors[] = "Цена должна быть положительным числом.";
        if ($available < 0) $errors[] = "Количество не может быть отрицательным.";
        if (empty($short_desc)) $errors[] = "Краткое описание обязательно.";
        if (empty($desc)) $errors[] = "Подробное описание обязательно.";
        
        $imageSql = "";
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $base64Image = processImageBase64($_FILES['image']);
            if ($base64Image) {
                $imageSql = ", image = '$base64Image'";
            } else {
                $errors[] = "Загруженный файл не является корректным изображением. Изменение фото отменено.";
            }
        }

        if (empty($errors)) {
            $name = mysqli_real_escape_string($conn, $name);
            $alias = mysqli_real_escape_string($conn, $alias);
            $short_desc = mysqli_real_escape_string($conn, $short_desc);
            $desc = mysqli_real_escape_string($conn, $desc);
            
            $sql = "UPDATE product SET name='$name', alias='$alias', price='$price', available='$available', short_description='$short_desc', description='$desc' $imageSql WHERE id=$id";
            if (mysqli_query($conn, $sql)) {
                $success_msg = "Товар успешно обновлен!";
            } else {
                $db_error = mysqli_error($conn);
            }
        } else {
            $db_error = implode("<br>", $errors);
        }
    }

    // =======================================================
    // 3. ОБРАБОТКА УДАЛЕНИЯ ТОВАРА
    // =======================================================
    if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
        $id = intval($_GET['id']);
        mysqli_query($conn, "DELETE FROM product WHERE id=$id");
        header("Location: admin.php");
        exit;
    }
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
                <li><a href="admin.php?logout=true" style="background-color: #c0392b;">Выйти из Админки</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <main>
        <?php 
        // ---------------------------------------------------------
        // ЕСЛИ АДМИН НЕ АВТОРИЗОВАН
        // ---------------------------------------------------------
        if (!isset($_SESSION['is_admin'])): 
        ?>
            <h2 style="text-align: center; width: 100%;">Вход в Админ-панель</h2>
            <?php if(isset($error_msg)) echo $error_msg; ?>
            <div class="form-container" style="max-width: 400px; margin-top: 30px;">
                <form action="admin.php" method="post">
                    <input type="hidden" name="login_admin" value="1">
                    <div class="form-group"><label class="title">Пароль:</label><input type="password" name="password" required placeholder="Пароль: admin"></div>
                    <button type="submit">Войти</button>
                </form>
            </div>
            
        <?php 
        // ---------------------------------------------------------
        // ЕСЛИ АДМИН АВТОРИЗОВАН
        // ---------------------------------------------------------
        else: 
            // Вывод сообщений об успехе или ошибке
            if (isset($success_msg)) echo "<div style='color:green; text-align:center; font-weight:bold; font-size:18px; padding:15px; border:2px solid green; border-radius:5px; background:#e8f5e9; margin-bottom:20px;'>$success_msg</div>";
            if (isset($db_error)) echo "<div style='color:red; text-align:center; padding:15px; border:2px solid red; border-radius:5px; background:#ffebee; margin-bottom:20px;'>Ошибка: $db_error</div>";
            
            // ПРОВЕРКА: Если мы нажали кнопку "Редактировать"
            if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])):
                $id = intval($_GET['id']);
                $res = mysqli_query($conn, "SELECT * FROM product WHERE id=$id");
                $prod = mysqli_fetch_assoc($res);
                if ($prod):
        ?>
                <!-- ================= ФОРМА РЕДАКТИРОВАНИЯ ================= -->
                <h2>Редактирование товара: <span style="color:#e67e22;"><?= htmlspecialchars($prod['name']) ?></span></h2>
                <div class="form-container">
                    <form action="admin.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="edit_product" value="1">
                        <input type="hidden" name="product_id" value="<?= $prod['id'] ?>">
                        
                        <div class="form-group"><label class="title">Название:</label>
                        <input type="text" name="name" value="<?= htmlspecialchars($prod['name']) ?>" required></div>
                        
                        <div class="form-group"><label class="title">Алиас (url-имя):</label>
                        <input type="text" name="alias" value="<?= htmlspecialchars($prod['alias']) ?>" required></div>
                        
                        <div class="form-group"><label class="title">Цена (руб):</label>
                        <input type="number" step="0.01" name="price" value="<?= $prod['price'] ?>" required min="0.01"></div>
                        
                        <div class="form-group"><label class="title">Количество в наличии:</label>
                        <input type="number" name="available" value="<?= $prod['available'] ?>" required min="0"></div>
                        
                        <div class="form-group" style="background:#f9f9f9; padding:15px; border-radius:5px; border:1px dashed #ccc;">
                            <label class="title">Новое фото товара (Оставьте пустым, чтобы сохранить текущее):</label>
                            <input type="file" name="image" accept="image/*" style="padding-top: 10px; margin-bottom:10px;">
                            <div>Текущее фото: <img src="<?= $prod['image'] ?>" style="width:50px; height:50px; object-fit:cover; border-radius:4px; vertical-align:middle;"></div>
                        </div>

                        <div class="form-group"><label class="title">Краткое описание (в каталог):</label>
                        <textarea name="short_desc" rows="2" required><?= htmlspecialchars($prod['short_description']) ?></textarea></div>
                        
                        <div class="form-group"><label class="title">ПОДРОБНОЕ описание (на страницу товара):</label>
                        <textarea name="desc" rows="6" required><?= htmlspecialchars($prod['description']) ?></textarea></div>
                        
                        <div style="display:flex; gap:10px;">
                            <button type="submit" style="flex:2;">💾 Сохранить изменения</button>
                            <button type="button" onclick="window.location.href='admin.php'" style="flex:1; background-color:#7f8c8d;">❌ Отмена</button>
                        </div>
                    </form>
                </div>

        <?php 
                endif; // Конец проверки существования товара $prod
            else: 
        ?>
                <!-- ================= ФОРМА ДОБАВЛЕНИЯ ================= -->
                <h2>Добавление нового товара</h2>
                <div class="form-container">
                    <form action="admin.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="add_product" value="1">
                        <div class="form-group"><label class="title">Название:</label><input type="text" name="name" required></div>
                        <div class="form-group"><label class="title">Алиас (url-имя):</label><input type="text" name="alias" required placeholder="primer-tovara"></div>
                        <div class="form-group"><label class="title">Цена (руб):</label><input type="number" step="0.01" name="price" required min="0.01"></div>
                        <div class="form-group"><label class="title">Количество в наличии:</label><input type="number" name="available" value="10" required min="0"></div>
                        <div class="form-group"><label class="title">Фото товара (будет обрезано 800x800):</label><input type="file" name="image" accept="image/*" required style="padding-top: 10px;"></div>
                        <div class="form-group"><label class="title">Краткое описание (в каталог):</label><textarea name="short_desc" rows="2" required></textarea></div>
                        <div class="form-group"><label class="title">ПОДРОБНОЕ описание (на страницу товара):</label><textarea name="desc" rows="6" required></textarea></div>
                        <button type="submit">➕ Добавить товар в БД</button>
                    </form>
                </div>
            <?php endif; // Конец блока Добавление/Редактирование ?>

            <!-- ================= ТАБЛИЦА ВСЕХ ТОВАРОВ ================= -->
            <hr style="margin: 40px 0;">
            <h2>Список товаров (Управление)</h2>
            
            <p style="background: #f1f1f1; padding: 10px; border-radius: 5px; font-weight: 600;">
                Сортировать: 
                <a href="admin.php?sort=price_asc" style="color: #e67e22; margin: 0 10px;">Цене (возр)</a> | 
                <a href="admin.php?sort=price_desc" style="color: #e67e22; margin: 0 10px;">Цене (убыв)</a> | 
                <a href="admin.php?sort=name_asc" style="color: #e67e22; margin: 0 10px;">А-Я</a>
            </p>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Фото</th>
                            <th>Название</th>
                            <th>Цена</th>
                            <th>В наличии</th>
                            <th>Алиас</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $order_by = "id DESC";
                        if (isset($_GET['sort'])) {
                            if ($_GET['sort'] == 'price_asc') $order_by = "price ASC";
                            if ($_GET['sort'] == 'price_desc') $order_by = "price DESC";
                            if ($_GET['sort'] == 'name_asc') $order_by = "name ASC";
                        }

                        $result = mysqli_query($conn, "SELECT * FROM product ORDER BY $order_by");
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>
                                        <td><img src='{$row['image']}' style='width:40px; height:40px; border-radius:4px; object-fit:cover;'></td>
                                        <td><strong>{$row['name']}</strong></td>
                                        <td style='color:#e67e22; font-weight:bold;'>{$row['price']} руб.</td>
                                        <td>{$row['available']} шт.</td>
                                        <td style='color:#7f8c8d;'>{$row['alias']}</td>
                                        <td>
                                            <a href='admin.php?action=edit&id={$row['id']}' style='color:#2980b9; font-weight:bold; margin-right:10px; text-decoration:none;'>✏️ Редакт.</a>
                                            <a href='admin.php?action=delete&id={$row['id']}' onclick=\"return confirm('Вы точно хотите удалить этот товар?');\" style='color:#c0392b; font-weight:bold; text-decoration:none;'>❌ Удал.</a>
                                        </td>
                                       </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>В базе данных пока нет товаров.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

        <?php endif; ?>
    </main>
    
    <footer style="position: relative;">
        <p>&copy; 2026 Сити-Класс. Все права защищены.</p>
        <p style="font-size: 12px; margin-top: 10px;">
            <a href="#" onclick="openPrivacyPolicy(); return false;" style="color: #e67e22;">Читать Политику конфиденциальности (Окно)</a> | 
            <a href="privacy.txt" download style="color: #3498db;">Скачать Политику (TXT)</a>
        </p>
    </footer>
    
    <script src="script.js"></script>
</body>
</html>