<?php
session_start();
$_SESSION['is_admin'] = true;
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST = [
    'id' => 1,
    'section_id' => 1,
    'title_ru' => 'Новый урок для проверки',
    'slug' => 'novy-urok-dlya-proverki',
    'lesson_order' => 3,
    'theory' => '<p>Теория</p>',
    'lab' => '<p>Лабораторная</p>',
    'tests_text' => "Вопрос 1?\nОтвет 1\nОтвет 2 ✔\nОтвет 3\nОтвет 4\n"
];

define('BASE_PATH', __DIR__);
define('CONFIG_PATH', BASE_PATH . '\config');
define('MODELS_PATH', BASE_PATH . '\models');
define('TEMPLATES_PATH', BASE_PATH . '\templates');

require __DIR__ . '/config/database.php';
require __DIR__ . '/models/Database.php';
require __DIR__ . '/models/Section.php';
require __DIR__ . '/models/Lesson.php';
require __DIR__ . '/controllers/AdminController.php';

$controller = new AdminController();
$controller->updateLesson();
