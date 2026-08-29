<?php
/**
 * Front Controller - точка входа для всех HTTP-запросов
 * Обрабатывает маршрутизацию и подключает соответствующие контроллеры
 * 
 * @author IT-Tools Project
 * @version 1.0
 */

// Запуск сессии для управления авторизацией
session_start();

// Установка кодировки
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');

// Определение базовых констант
define('BASE_PATH', __DIR__);
define('CONFIG_PATH', BASE_PATH . '/config');
define('CONTROLLERS_PATH', BASE_PATH . '/controllers');
define('MODELS_PATH', BASE_PATH . '/models');
define('TEMPLATES_PATH', BASE_PATH . '/templates');
define('PUBLIC_PATH', BASE_PATH . '/public');

// Подключение файлов конфигурации и моделей
require_once CONFIG_PATH . '/database.php';
require_once MODELS_PATH . '/Database.php';
require_once MODELS_PATH . '/Section.php';
require_once MODELS_PATH . '/Lesson.php';
require_once CONTROLLERS_PATH . '/AdminController.php';

// Получение запрошенного URI
$requestUri = $_SERVER['REQUEST_URI'];
// Удаление базового пути /it-tools из URI
$requestUri = str_replace('/it-tools', '', $requestUri);
// Удаление начального и конечного слешей
$requestUri = trim($requestUri, '/');

// Отделяем query string от пути
$queryString = '';
if (strpos($requestUri, '?') !== false) {
    list($requestUri, $queryString) = explode('?', $requestUri, 2);
    // Разбираем query string в $_GET (на случай если она была потеряна)
    parse_str($queryString, $queryArray);
    foreach ($queryArray as $key => $value) {
        $_GET[$key] = $value;
    }
}

// Разделение URI на части
$parts = explode('/', $requestUri);

// Маршрутизация запросов
if (empty($requestUri) || $requestUri === '') {
    // Главная страница - список разделов
    require_once CONTROLLERS_PATH . '/HomeController.php';
    $controller = new HomeController();
    $controller->index();
    
} elseif ($parts[0] === 'bod') {
    // Админ-панель
    $controller = new AdminController();
    
    // Обработка действий админ-панели
    if (isset($parts[1])) {
        $action = $parts[1];
        // Преобразуем kebab-case в CamelCase (create-section -> createSection)
        $camelCaseAction = str_replace(' ', '', ucwords(str_replace('-', ' ', $action)));
        $camelCaseAction = lcfirst($camelCaseAction);
        
        if (method_exists($controller, $camelCaseAction)) {
            $controller->$camelCaseAction();
        } else {
            $controller->dashboard();
        }
    } else {
        $controller->login();
    }
    
} elseif (count($parts) === 2) {
    // Страница урока: section_order-slug/lesson_order-slug
    require_once CONTROLLERS_PATH . '/LessonController.php';
    $controller = new LessonController();
    
    // Парсинг параметров урока
    $sectionPart = $parts[0]; // section_order-slug
    $lessonPart = $parts[1];  // lesson_order-slug
    
    // Разделение order и slug
    $sectionParts = explode('-', $sectionPart, 2);
    $lessonParts = explode('-', $lessonPart, 2);
    
    if (count($sectionParts) === 2 && count($lessonParts) === 2) {
        $sectionOrder = $sectionParts[0];
        $sectionSlug = $sectionParts[1];
        $lessonOrder = $lessonParts[0];
        $lessonSlug = $lessonParts[1];
        
        $controller->show($sectionOrder, $sectionSlug, $lessonOrder, $lessonSlug);
    } else {
        // Некорректный формат URL - страница 404
        require_once TEMPLATES_PATH . '/404.php';
    }
    
} else {
    // Некорректный URL - страница 404
    require_once TEMPLATES_PATH . '/404.php';
}