<?php
    $dsn = "mysql:host=localhost;dbname=authdata";    // Налаштування підключення
    $user = 'root';
    $pass = '';

    try {
        $dbh = new PDO($dsn, $user, $pass); // Створення підключення
    }

    catch(PDOException $ex) {   // Обробка виключень
        echo $ex->GetMessage();     // Виведення повідомлення про помилку
    }
?>