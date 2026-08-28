<?php
/**
 * Контроллер страницы урока
 * Отображает урок с тремя секциями: теория, тесты, лабораторная работа
 * 
 * @author IT-Tools Project
 * @version 1.0
 */

class LessonController {
    private $sectionModel;
    private $lessonModel;
    
    /**
     * Конструктор - инициализирует модели
     */
    public function __construct() {
        $db = new Database();
        $this->sectionModel = new Section($db);
        $this->lessonModel = new Lesson($db);
    }
    
    /**
     * Отображает страницу урока
     * 
     * @param int $sectionOrder Порядковый номер раздела
     * @param string $sectionSlug Slug раздела
     * @param int $lessonOrder Порядковый номер урока
     * @param string $lessonSlug Slug урока
     */
    public function show($sectionOrder, $sectionSlug, $lessonOrder, $lessonSlug) {
        // Получение урока по параметрам URL
        $lesson = $this->lessonModel->getByOrderAndSlug($sectionOrder, $sectionSlug, $lessonOrder, $lessonSlug);
        
        if (!$lesson) {
            // Урок не найден - страница 404
            require_once TEMPLATES_PATH . '/404.php';
            return;
        }
        
        // Получение информации о разделе
        $section = $this->sectionModel->getById($lesson['section_id']);
        
        // Получение предыдущего и следующего урока
        $previousLesson = $this->lessonModel->getPreviousLesson($lesson['section_id'], $lesson['lesson_order']);
        $nextLesson = $this->lessonModel->getNextLesson($lesson['section_id'], $lesson['lesson_order']);
        
        // Формирование URL для навигации
        $prevUrl = null;
        $nextUrl = null;
        
        if ($previousLesson) {
            $prevSection = $this->sectionModel->getById($previousLesson['section_id']);
            $prevUrl = "/it-tools/{$prevSection['section_order']}-{$prevSection['slug']}/{$previousLesson['lesson_order']}-{$previousLesson['slug']}";
        }
        
        if ($nextLesson) {
            $nextSection = $this->sectionModel->getById($nextLesson['section_id']);
            $nextUrl = "/it-tools/{$nextSection['section_order']}-{$nextSection['slug']}/{$nextLesson['lesson_order']}-{$nextLesson['slug']}";
        }
        
        // Подключение шаблона с передачей данных
        require_once TEMPLATES_PATH . '/header.php';
        require_once TEMPLATES_PATH . '/lesson.php';
        require_once TEMPLATES_PATH . '/footer.php';
    }
}