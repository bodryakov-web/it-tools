<?php
/**
 * Класс Lesson для работы с уроками
 * Обеспечивает CRUD операции для таблицы lessons и обработку JSON контента
 * 
 * @author IT-Tools Project
 * @version 1.0
 */

class Lesson {
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
     * Получает все уроки для раздела, отсортированные по порядковому номеру
     * 
     * @param int $sectionId ID раздела
     * @return array Массив уроков
     */
    public function getBySectionId($sectionId) {
        $sql = "SELECT * FROM lessons WHERE section_id = :section_id ORDER BY lesson_order ASC";
        return $this->db->query($sql, ['section_id' => $sectionId]);
    }
    
    /**
     * Получает урок по ID
     * 
     * @param int $id ID урока
     * @return array|false Данные урока или false
     */
    public function getById($id) {
        $sql = "SELECT * FROM lessons WHERE id = :id";
        $result = $this->db->queryOne($sql, ['id' => $id]);
        if ($result) {
            $result['content'] = json_decode($result['content'], true);
        }
        return $result;
    }
    
    /**
     * Получает урок по порядковому номеру и slug раздела и урока
     * 
     * @param int $sectionOrder Порядковый номер раздела
     * @param string $sectionSlug Slug раздела
     * @param int $lessonOrder Порядковый номер урока
     * @param string $lessonSlug Slug урока
     * @return array|false Данные урока или false
     */
    public function getByOrderAndSlug($sectionOrder, $sectionSlug, $lessonOrder, $lessonSlug) {
        $sql = "SELECT l.* FROM lessons l 
                JOIN sections s ON l.section_id = s.id 
                WHERE s.section_order = :section_order AND s.slug = :section_slug 
                AND l.lesson_order = :lesson_order AND l.slug = :lesson_slug";
        $params = [
            'section_order' => $sectionOrder,
            'section_slug' => $sectionSlug,
            'lesson_order' => $lessonOrder,
            'lesson_slug' => $lessonSlug
        ];
        $result = $this->db->queryOne($sql, $params);
        if ($result) {
            $result['content'] = json_decode($result['content'], true);
        }
        return $result;
    }
    
    /**
     * Создаёт новый урок
     * 
     * @param array $data Данные урока (section_id, title_ru, slug, lesson_order, content)
     * @return bool true при успехе, false при ошибке
     */
    public function create($data) {
        $contentJson = json_encode($data['content'], JSON_UNESCAPED_UNICODE);
        $sql = "INSERT INTO lessons (section_id, title_ru, slug, lesson_order, content) 
                VALUES (:section_id, :title_ru, :slug, :lesson_order, :content)";
        $params = [
            'section_id' => $data['section_id'],
            'title_ru' => $data['title_ru'],
            'slug' => $data['slug'],
            'lesson_order' => $data['lesson_order'],
            'content' => $contentJson
        ];
        return $this->db->execute($sql, $params);
    }
    
    /**
     * Обновляет существующий урок
     * 
     * @param int $id ID урока
     * @param array $data Данные для обновления
     * @return bool true при успехе, false при ошибке
     */
    public function update($id, $data) {
        $contentJson = json_encode($data['content'], JSON_UNESCAPED_UNICODE);
        $sql = "UPDATE lessons SET section_id = :section_id, title_ru = :title_ru, 
                slug = :slug, lesson_order = :lesson_order, content = :content WHERE id = :id";
        $params = [
            'section_id' => $data['section_id'],
            'title_ru' => $data['title_ru'],
            'slug' => $data['slug'],
            'lesson_order' => $data['lesson_order'],
            'content' => $contentJson,
            'id' => $id
        ];
        return $this->db->execute($sql, $params);
    }
    
    /**
     * Удаляет урок по ID
     * 
     * @param int $id ID урока
     * @return bool true при успехе, false при ошибке
     */
    public function delete($id) {
        $sql = "DELETE FROM lessons WHERE id = :id";
        return $this->db->execute($sql, ['id' => $id]);
    }
    
    /**
     * Проверяет уникальность slug в пределах раздела
     * 
     * @param int $sectionId ID раздела
     * @param string $slug Slug для проверки
     * @param int|null $excludeId ID урока для исключения (при редактировании)
     * @return bool true если slug уникален, false если занят
     */
    public function isSlugUnique($sectionId, $slug, $excludeId = null) {
        if ($excludeId) {
            $sql = "SELECT COUNT(*) as count FROM lessons WHERE section_id = :section_id AND slug = :slug AND id != :id";
            $result = $this->db->queryOne($sql, ['section_id' => $sectionId, 'slug' => $slug, 'id' => $excludeId]);
        } else {
            $sql = "SELECT COUNT(*) as count FROM lessons WHERE section_id = :section_id AND slug = :slug";
            $result = $this->db->queryOne($sql, ['section_id' => $sectionId, 'slug' => $slug]);
        }
        return $result['count'] == 0;
    }
    
    /**
     * Проверяет уникальность порядкового номера в пределах раздела
     * 
     * @param int $sectionId ID раздела
     * @param int $order Порядковый номер для проверки
     * @param int|null $excludeId ID урока для исключения (при редактировании)
     * @return bool true если номер уникален, false если занят
     */
    public function isOrderUnique($sectionId, $order, $excludeId = null) {
        if ($excludeId) {
            $sql = "SELECT COUNT(*) as count FROM lessons WHERE section_id = :section_id AND lesson_order = :order AND id != :id";
            $result = $this->db->queryOne($sql, ['section_id' => $sectionId, 'order' => $order, 'id' => $excludeId]);
        } else {
            $sql = "SELECT COUNT(*) as count FROM lessons WHERE section_id = :section_id AND lesson_order = :order";
            $result = $this->db->queryOne($sql, ['section_id' => $sectionId, 'order' => $order]);
        }
        return $result['count'] == 0;
    }
    
    /**
     * Проверяет уникальность названия в пределах раздела
     * 
     * @param int $sectionId ID раздела
     * @param string $title Название для проверки
     * @param int|null $excludeId ID урока для исключения (при редактировании)
     * @return bool true если название уникально, false если занято
     */
    public function isTitleUnique($sectionId, $title, $excludeId = null) {
        if ($excludeId) {
            $sql = "SELECT COUNT(*) as count FROM lessons WHERE section_id = :section_id AND title_ru = :title AND id != :id";
            $result = $this->db->queryOne($sql, ['section_id' => $sectionId, 'title' => $title, 'id' => $excludeId]);
        } else {
            $sql = "SELECT COUNT(*) as count FROM lessons WHERE section_id = :section_id AND title_ru = :title";
            $result = $this->db->queryOne($sql, ['section_id' => $sectionId, 'title' => $title]);
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
        return preg_match('/^[a-z-]+$/', $slug) && !empty($slug);
    }
    
    /**
     * Получает предыдущий урок в том же разделе
     * 
     * @param int $sectionId ID раздела
     * @param int $currentOrder Текущий порядковый номер
     * @return array|false Данные предыдущего урока или false
     */
    public function getPreviousLesson($sectionId, $currentOrder) {
        $sql = "SELECT * FROM lessons WHERE section_id = :section_id AND lesson_order < :order ORDER BY lesson_order DESC LIMIT 1";
        return $this->db->queryOne($sql, ['section_id' => $sectionId, 'order' => $currentOrder]);
    }
    
    /**
     * Получает следующий урок в том же разделе
     * 
     * @param int $sectionId ID раздела
     * @param int $currentOrder Текущий порядковый номер
     * @return array|false Данные следующего урока или false
     */
    public function getNextLesson($sectionId, $currentOrder) {
        $sql = "SELECT * FROM lessons WHERE section_id = :section_id AND lesson_order > :order ORDER BY lesson_order ASC LIMIT 1";
        return $this->db->queryOne($sql, ['section_id' => $sectionId, 'order' => $currentOrder]);
    }
}