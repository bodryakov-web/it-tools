# Структура проекта IT-Tools

## Общая структура директорий

```
it-tools/
├── .docs/
│   ├── structure.md
│   ├── description.md
│   ├── pipeline.md
│   └── Technical specifications for creating an application IT-Tools .txt
├── .htaccess
├── index.php
├── config/
│   └── database.php
├── public/
│   ├── css/
│   │   └── styles.css
│   ├── js/
│   │   ├── main.js
│   │   └── tests.js
│   └── images/
│       └── (если понадобятся статические изображения)
├── templates/
│   ├── header.php
│   ├── footer.php
│   ├── home.php
│   ├── lesson.php
│   ├── 404.php
│   └── admin/
│       ├── login.php
│       ├── dashboard.php
│       ├── section_form.php
│       ├── lesson_form.php
│       └── test_converter.php
├── controllers/
│   ├── HomeController.php
│   ├── LessonController.php
│   ├── AdminController.php
│   └── SectionController.php
├── models/
│   ├── Database.php
│   ├── Section.php
│   └── Lesson.php
└── sql/
    ├── schema.sql
    └── test_data.sql
```

## Описание директорий и файлов

### Корневые файлы
- **.htaccess** - Конфигурация Apache для перенаправления всех запросов на index.php
- **index.php** - Front Controller, точка входа для всех HTTP-запросов

### Директория .docs/
- **structure.md** - Файл со структурой проекта (этот файл)
- **description.md** - Текстовое описание проекта
- **pipeline.md** - Последовательность создания проекта
- **Technical specifications for creating an application IT-Tools .txt** - Исходное техническое задание

### Директория config/
- **database.php** - Файл подключения к базе данных MySQL с hardcode credential'ами

### Директория public/
- **css/styles.css** - Основной файл стилей (CSS по методологии БЭМ, OKLCH цвета, Mobile First)
- **js/main.js** - Основной JavaScript файл для интерактивности
- **js/tests.js** - JavaScript для функционала тестов
- **images/** - Статические изображения (если понадобятся)

### Директория templates/
- **header.php** - Шапка сайта с логотипом и навигацией
- **footer.php** - Подвал сайта с авторской информацией
- **home.php** - Главная страница со списком разделов и уроков
- **lesson.php** - Шаблон страницы урока
- **404.php** - Страница ошибки 404
- **admin/login.php** - Форма входа в админ-панель
- **admin/dashboard.php** - Главная страница админ-панели
- **admin/section_form.php** - Форма создания/редактирования раздела
- **admin/lesson_form.php** - Форма создания/редактирования урока с CKEditor 5
- **admin/test_converter.php** - Интерфейс для преобразования тестов в JSON

### Директория controllers/
- **HomeController.php** - Контроллер для главной страницы
- **LessonController.php** - Контроллер для отображения уроков
- **AdminController.php** - Контроллер для админ-панели (авторизация, CRUD)
- **SectionController.php** - Контроллер для управления разделами

### Директория models/
- **Database.php** - Класс для работы с базой данных (PDO)
- **Section.php** - Модель раздела (CRUD операции)
- **Lesson.php** - Модель урока (CRUD операции, JSON обработка)

### Директория sql/
- **schema.sql** - SQL скрипт для создания структуры таблиц
- **test_data.sql** - SQL скрипт для заполнения тестовыми данными

## Технические детали структуры

### Организация MVC паттерна
- **Models** - Логика работы с базой данных и бизнес-логика
- **Views** - HTML шаблоны в директории templates/
- **Controllers** - Обработка HTTP-запросов и координация между models и views

### Организация админ-панели
- Все админские шаблоны в директории templates/admin/
- Доступ по URL /bod
- Авторизация через PHP сессии

### Статические ресурсы
- CSS в public/css/
- JavaScript в public/js/
- Изображения в public/images/

### База данных
- SQL скрипты для развёртывания в директории sql/
- Подключение через PDO в config/database.php

## Особенности структуры

1. **Чистый PHP без фреймворков** - Собственная реализация MVC
2. **Front Controller Pattern** - Все запросы через index.php
3. **Разделение concerns** - Чёткое разделение на models, views, controllers
4. **Адаптивность для хостинга** - Простая структура для деплоя на обычный хостинг
5. **UTF-8 без BOM** - Все файлы в кодировке UTF-8 без BOM для поддержки кириллицы