<?php
$host = 'db'; // Имя контейнера MySQL в Docker
$dbname = 'site';
$username = 'root';
$password = 'root';

// Создаем соединение
$conn = mysqli_connect($host, $username, $password, $dbname);

// Проверяем соединение
if (!$conn) {
    die("Ошибка подключения: " . mysqli_connect_error());
}

// Устанавливаем кодировку
mysqli_set_charset($conn, "utf8mb4");
?>