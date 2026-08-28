<?php
/**
 * Класс Database для работы с базой данных через PDO
 * Обеспечивает подключение и базовые методы для выполнения запросов
 * 
 * @author IT-Tools Project
 * @version 1.0
 */

require_once CONFIG_PATH . '/database.php';

class Database {
    private $pdo;
    
    /**
     * Конструктор - устанавливает подключение к базе данных
     */
    public function __construct() {
        $this->pdo = getDbConnection();
    }
    
    /**
     * Выполняет SELECT запрос и возвращает все результаты
     * 
     * @param string $sql SQL запрос с плейсхолдерами
     * @param array $params Параметры для плейсхолдеров
     * @return array Массив результатов
     */
    public function query($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Database query error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Выполняет SELECT запрос и возвращает одну строку
     * 
     * @param string $sql SQL запрос с плейсхолдерами
     * @param array $params Параметры для плейсхолдеров
     * @return array|false Одно значение или false если не найдено
     */
    public function queryOne($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Database query error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Выполняет INSERT, UPDATE, DELETE запросы
     * 
     * @param string $sql SQL запрос с плейсхолдерами
     * @param array $params Параметры для плейсхолдеров
     * @return bool true при успехе, false при ошибке
     */
    public function execute($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Database execute error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Возвращает ID последней вставленной записи
     * 
     * @return string ID последней вставленной записи
     */
    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }
    
    /**
     * Начинает транзакцию
     * 
     * @return bool true при успехе
     */
    public function beginTransaction() {
        return $this->pdo->beginTransaction();
    }
    
    /**
     * Подтверждает транзакцию
     * 
     * @return bool true при успехе
     */
    public function commit() {
        return $this->pdo->commit();
    }
    
    /**
     * Откатывает транзакцию
     * 
     * @return bool true при успехе
     */
    public function rollBack() {
        return $this->pdo->rollBack();
    }
    
    /**
     * Закрывает подключение к базе данных
     */
    public function __destruct() {
        $this->pdo = null;
    }
}