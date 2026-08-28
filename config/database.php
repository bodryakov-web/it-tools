<?php
/**
 * Конфигурация подключения к базе данных MySQL
 * Credential'ы зашиты в коде согласно требованиям ТЗ
 * 
 * @author IT-Tools Project
 * @version 1.0
 */

// Параметры подключения к базе данных
define('DB_HOST', 'localhost');
define('DB_NAME', 'p-351366_it-tools');
define('DB_USER', 'p-351366_it-tools');
define('DB_PASS', 'Anna-140275');
define('DB_CHARSET', 'utf8mb4');

// Credential'ы администратора для входа в админ-панель
define('ADMIN_LOGIN', 'bodryakov.web');
define('ADMIN_PASSWORD', 'Anna-140275');

// Функция для получения PDO подключения к базе данных
function getDbConnection() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $pdo;
        
    } catch (PDOException $e) {
        // В реальном приложении здесь должна быть более детальная обработка ошибок
        die("Ошибка подключения к базе данных: " . $e->getMessage());
    }
}