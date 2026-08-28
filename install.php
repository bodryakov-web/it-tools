<?php
/**
 * Скрипт инициализации базы данных
 * Выполняет SQL скрипты для создания структуры и заполнения тестовыми данными
 * 
 * @author IT-Tools Project
 * @version 1.0
 */

// Подключение конфигурации
require_once 'config/database.php';

try {
    // Получение подключения к базе данных
    $pdo = getDbConnection();
    
    echo "<h1>Инициализация базы данных IT-Tools</h1>";
    
    // Очистка существующих данных
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    $pdo->exec("TRUNCATE TABLE lessons");
    $pdo->exec("TRUNCATE TABLE sections");
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    echo "<p style='color: blue;'>ℹ Существующие данные очищены</p>";
    
    // Чтение и выполнение schema.sql
    $schemaSql = file_get_contents('sql/schema.sql');
    if ($schemaSql) {
        $pdo->exec($schemaSql);
        echo "<p style='color: green;'>✓ Структура базы данных создана успешно</p>";
    } else {
        echo "<p style='color: red;'>✗ Ошибка чтения schema.sql</p>";
    }
    
    // Чтение и выполнение test_data.sql
    $testDataSql = file_get_contents('sql/test_data.sql');
    if ($testDataSql) {
        $pdo->exec($testDataSql);
        echo "<p style='color: green;'>✓ Тестовые данные добавлены успешно</p>";
    } else {
        echo "<p style='color: red;'>✗ Ошибка чтения test_data.sql</p>";
    }
    
    echo "<p style='color: green; font-weight: bold;'>✓ Инициализация базы данных завершена успешно!</p>";
    echo "<p><a href='/it-tools/'>Перейти на главную страницу</a></p>";
    echo "<p><a href='/it-tools/bod/login'>Перейти в админ-панель</a></p>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>✗ Ошибка базы данных: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Убедитесь, что:</p>";
    echo "<ul>";
    echo "<li>MySQL сервер запущен</li>";
    echo "<li>Учетные данные в config/database.php верны</li>";
    echo "<li>База данных p-351366_it-tools существует</li>";
    echo "</ul>";
}