<?php
/**
 * Контроллер главной страницы
 * Отображает список разделов и уроков в виде карточек
 * 
 * @author IT-Tools Project
 * @version 1.0
 */

class HomeController {
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
     * Отображает главную страницу со списком разделов и уроков
     */
    public function index() {
        // Получение всех разделов
        $sections = $this->sectionModel->getAll();
        
        // Получение уроков для каждого раздела
        $sectionsWithLessons = [];
        foreach ($sections as $section) {
            $lessons = $this->lessonModel->getBySectionId($section['id']);
            $section['lessons'] = $lessons;
            $sectionsWithLessons[] = $section;
        }
        
        // Подключение шаблона
        require_once TEMPLATES_PATH . '/header.php';
        require_once TEMPLATES_PATH . '/home.php';
        require_once TEMPLATES_PATH . '/footer.php';
    }
}