<?php

function getConnection() {
    // Если запущено в Docker (переменная окружения DB_HOST задана), используем db иначе 127.0.1.31
    $host = getenv('DB_HOST') ?: 'db';
    $dbname = getenv('DB_NAME') ?: 'gamepulse';
    $user = getenv('DB_USER') ?: 'root';
    $password = getenv('DB_PASS') ?: 'root';
    
    // Для OpenPanel пароль пустой, для Docker пароль root (уже задан выше)
    if ($host === '127.0.1.31') {
        $password = '';
    }
    
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";
    
    try {
        $pdo = new PDO($dsn, $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return array(true, $pdo);
    } catch (PDOException $e) {
        return array(false, 'Ошибка подключения: ' . $e->getMessage());
    }
}

?>