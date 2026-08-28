-- SQL скрипт для создания структуры базы данных IT-Tools
-- Создаёт таблицы sections и lessons с кодировкой utf8mb4_unicode_ci
-- 
-- @author IT-Tools Project
-- @version 1.0

-- Установка кодировки соединения
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- Создание таблицы sections
CREATE TABLE IF NOT EXISTS sections (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title_ru VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    section_order INT NOT NULL,
    UNIQUE KEY unique_slug (slug),
    UNIQUE KEY unique_order (section_order),
    INDEX idx_section_order (section_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Создание таблицы lessons
CREATE TABLE IF NOT EXISTS lessons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    section_id INT NOT NULL,
    title_ru VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    lesson_order INT NOT NULL,
    content JSON NOT NULL,
    FOREIGN KEY (section_id) REFERENCES sections(id) ON DELETE CASCADE,
    UNIQUE KEY unique_section_lesson (section_id, slug),
    UNIQUE KEY unique_section_order (section_id, lesson_order),
    INDEX idx_section_id (section_id),
    INDEX idx_lesson_order (lesson_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;