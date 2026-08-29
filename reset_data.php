<?php
/**
 * Скрипт для очистки и перезагрузки тестовых данных
 */

define('BASE_PATH', __DIR__);
define('CONFIG_PATH', BASE_PATH . '/config');

require BASE_PATH . '/config/database.php';
require BASE_PATH . '/models/Database.php';

$db = new Database();

// Очистка таблиц
echo "Очистка таблиц...\n";
$db->execute("TRUNCATE TABLE lessons");
$db->execute("TRUNCATE TABLE sections");

// Установка кодировки
$db->execute("SET NAMES utf8mb4");
$db->execute("SET CHARACTER SET utf8mb4");

// Вставка тестового раздела
echo "Вставка раздела...\n";
$sectionSql = "INSERT INTO sections (title_ru, slug, section_order) VALUES ('Введение в Linux', 'introduction-to-linux', 1)";
$db->execute($sectionSql);

// Получение ID вставленного раздела
$sections = $db->query("SELECT id FROM sections ORDER BY id DESC LIMIT 1");
$sectionId = $sections[0]['id'];

// Вставка тестового урока с чистыми данными
echo "Вставка урока...\n";
$testContent = json_encode([
    "theory" => "<h2>Basic Linux Commands</h2><p>Command line interface for system interaction.</p><h3>Basic Commands</h3><ul><li><code>ls</code> - list directory contents</li><li><code>cd</code> - change directory</li><li><code>pwd</code> - print working directory</li><li><code>mkdir</code> - make directory</li><li><code>rm</code> - remove files</li></ul>",
    "tests" => [
        [
            "question" => "What does ls command do?",
            "answers" => ["List directory (correct)", "Current directory", "Create directory", "Remove files"]
        ],
        [
            "question" => "Which command changes directory?",
            "answers" => ["ls", "pwd", "cd (correct)", "mkdir"]
        ],
        [
            "question" => "What does mkdir do?",
            "answers" => ["List files", "Create directory (correct)", "Remove files", "Show path"]
        ]
    ],
    "lab" => "<h2>Lab: Basic Commands</h2><p>Practice with basic Linux commands.</p><h3>Task 1</h3><p>Create directory: <code>mkdir test_folder</code></p><h3>Task 2</h3><p>Go to directory: <code>cd test_folder</code></p><h3>Task 3</h3><p>List contents: <code>ls -la</code></p><h3>Task 4</h3><p>Go back: <code>cd ..</code></p><h3>Task 5</h3><p>Remove directory: <code>rm -r test_folder</code></p>"
], JSON_UNESCAPED_UNICODE);

$lessonSql = "INSERT INTO lessons (section_id, title_ru, slug, lesson_order, content) 
             VALUES (:section_id, :title_ru, :slug, :lesson_order, :content)";

$db->execute($lessonSql, [
    'section_id' => $sectionId,
    'title_ru' => 'Основы командной строки',
    'slug' => 'command-line-basics',
    'lesson_order' => 1,
    'content' => $testContent
]);

echo "✓ Данные успешно перезагружены!\n";
echo "Раздел ID: $sectionId\n";
?>
