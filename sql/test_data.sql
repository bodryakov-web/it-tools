-- SQL скрипт для заполнения базы данных тестовыми данными
-- Создаёт один раздел и один урок для тестирования
-- 
-- @author IT-Tools Project
-- @version 1.0

-- Установка кодировки соединения
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- Вставка тестового раздела
INSERT INTO sections (title_ru, slug, section_order) VALUES 
('Введение в Linux', 'introduction-to-linux', 1);

-- Получение ID вставленного раздела
SET @section_id = LAST_INSERT_ID();

-- Вставка тестового урока с упрощенным JSON (без кириллицы в JSON для избежания проблем с кодировкой)
INSERT INTO lessons (section_id, title_ru, slug, lesson_order, content) VALUES 
(@section_id, 'Основы командной строки', 'command-line-basics', 1, 
'{"theory":"<h2>Basic Linux Commands</h2><p>Command line interface for system interaction.</p><h3>Basic Commands</h3><ul><li><code>ls</code> - list directory contents</li><li><code>cd</code> - change directory</li><li><code>pwd</code> - print working directory</li><li><code>mkdir</code> - make directory</li><li><code>rm</code> - remove files</li></ul>","tests":[{"question":"What does ls command do?","answers":["List directory","Current directory","Create directory","Remove files"],"correct":0},{"question":"Which command changes directory?","answers":["ls","pwd","cd","mkdir"],"correct":2},{"question":"What does mkdir do?","answers":["List files","Create directory","Remove files","Show path"],"correct":1}],"lab":"<h2>Lab: Basic Commands</h2><p>Practice with basic Linux commands.</p><h3>Task 1</h3><p>Create directory: <code>mkdir test_folder</code></p><h3>Task 2</h3><p>Go to directory: <code>cd test_folder</code></p><h3>Task 3</h3><p>List contents: <code>ls -la</code></p><h3>Task 4</h3><p>Go back: <code>cd ..</code></p><h3>Task 5</h3><p>Remove directory: <code>rm -r test_folder</code></p>"}');