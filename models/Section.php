<?php
/**
 * Класс Section для работы с разделами курсов
 * Обеспечивает CRUD операции для таблицы sections
 * 
 * @author IT-Tools Project
 * @version 1.0
 */

class Section {
    private $db;
    
    /**
     * Конструктор - принимает объект Database
     * 
     * @param Database $db Объект базы данных
     */
    public function __construct($db) {
        $this->db = $db;
    }
    
    /**
     * Получает все разделы, отсортированные по порядковому номеру
     * 
     * @return array Массив разделов
     */
    public function getAll() {
        $sql = "SELECT * FROM sections ORDER BY section_order ASC";
        return $this->db->query($sql);
    }
    
    /**
     * Получает раздел по ID
     * 
     * @param int $id ID раздела
     * @return array|false Данные раздела или false
     */
    public function getById($id) {
        $sql = "SELECT * FROM sections WHERE id = :id";
        return $this->db->queryOne($sql, ['id' => $id]);
    }
    
    /**
     * Получает раздел по порядковому номеру и slug
     * 
     * @param int $order Порядковый номер
     * @param string $slug Slug раздела
     * @return array|false Данные раздела или false
     */
    public function getByOrderAndSlug($order, $slug) {
        $sql = "SELECT * FROM sections WHERE section_order = :order AND slug = :slug";
        return $this->db->queryOne($sql, ['order' => $order, 'slug' => $slug]);
    }
    
    /**
     * Создаёт новый раздел
     * 
     * @param array $data Данные раздела (title_ru, slug, section_order)
     * @return bool true при успехе, false при ошибке
     */
    public function create($data) {
        $sql = "INSERT INTO sections (title_ru, slug, section_order) VALUES (:title_ru, :slug, :section_order)";
        $params = [
            'title_ru' => $data['title_ru'],
            'slug' => $data['slug'],
            'section_order' => $data['section_order']
        ];
        return $this->db->execute($sql, $params);
    }
    
    /**
     * Обновляет существующий раздел
     * 
     * @param int $id ID раздела
     * @param array $data Данные для обновления
     * @return bool true при успехе, false при ошибке
     */
    public function update($id, $data) {
        $sql = "UPDATE sections SET title_ru = :title_ru, slug = :slug, section_order = :section_order WHERE id = :id";
        $params = [
            'title_ru' => $data['title_ru'],
            'slug' => $data['slug'],
            'section_order' => $data['section_order'],
            'id' => $id
        ];
        return $this->db->execute($sql, $params);
    }
    
    /**
     * Удаляет раздел по ID
     * 
     * @param int $id ID раздела
     * @return bool true при успехе, false при ошибке
     */
    public function delete($id) {
        $sql = "DELETE FROM sections WHERE id = :id";
        return $this->db->execute($sql, ['id' => $id]);
    }
    
    /**
     * Проверяет уникальность slug (глобально)
     * 
     * @param string $slug Slug для проверки
     * @param int|null $excludeId ID раздела для исключения (при редактировании)
     * @return bool true если slug уникален, false если занят
     */
    public function isSlugUnique($slug, $excludeId = null) {
        if ($excludeId) {
            $sql = "SELECT COUNT(*) as count FROM sections WHERE slug = :slug AND id != :id";
            $result = $this->db->queryOne($sql, ['slug' => $slug, 'id' => $excludeId]);
        } else {
            $sql = "SELECT COUNT(*) as count FROM sections WHERE slug = :slug";
            $result = $this->db->queryOne($sql, ['slug' => $slug]);
        }
        return $result['count'] == 0;
    }
    
    /**
     * Проверяет уникальность порядкового номера (глобально)
     * 
     * @param int $order Порядковый номер для проверки
     * @param int|null $excludeId ID раздела для исключения (при редактировании)
     * @return bool true если номер уникален, false если занят
     */
    public function isOrderUnique($order, $excludeId = null) {
        if ($excludeId) {
            $sql = "SELECT COUNT(*) as count FROM sections WHERE section_order = :order AND id != :id";
            $result = $this->db->queryOne($sql, ['order' => $order, 'id' => $excludeId]);
        } else {
            $sql = "SELECT COUNT(*) as count FROM sections WHERE section_order = :order";
            $result = $this->db->queryOne($sql, ['order' => $order]);
        }
        return $result['count'] == 0;
    }
    
    /**
     * Проверяет уникальность названия (глобально)
     * 
     * @param string $title Название для проверки
     * @param int|null $excludeId ID раздела для исключения (при редактировании)
     * @return bool true если название уникально, false если занято
     */
    public function isTitleUnique($title, $excludeId = null) {
        if ($excludeId) {
            $sql = "SELECT COUNT(*) as count FROM sections WHERE title_ru = :title AND id != :id";
            $result = $this->db->queryOne($sql, ['title' => $title, 'id' => $excludeId]);
        } else {
            $sql = "SELECT COUNT(*) as count FROM sections WHERE title_ru = :title";
            $result = $this->db->queryOne($sql, ['title' => $title]);
        }
        return $result['count'] == 0;
    }
    
    /**
     * Валидирует формат slug (только английские буквы и дефисы)
     * 
     * @param string $slug Slug для валидации
     * @return bool true если формат корректен, false если нет
     */
    public function validateSlugFormat($slug) {
        // Debug output - записываем в глобальную переменную для вывода в форме
        if (!isset($GLOBALS['debug_slug'])) {
            $GLOBALS['debug_slug'] = [];
        }
        $GLOBALS['debug_slug'][] = [
            'slug' => $slug,
            'empty' => empty($slug) ? 'empty' : 'not empty',
            'preg_match' => preg_match('/^[a-z-]+$/', $slug) ? 'true' : 'false'
        ];
        
        $result = preg_match('/^[a-z-]+$/', $slug) && !empty($slug);
        return $result;
    }
}