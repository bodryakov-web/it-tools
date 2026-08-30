# Структура проекта IT-Tools

## Общая структура директорий

```
it-tools/
├── .git/
├── .htaccess
├── index.php
├── config/
│   └── database.php
├── controllers/
│   ├── AdminController.php
│   ├── HomeController.php
│   └── LessonController.php
├── docs/
│   ├── structure.md
│   ├── description.md
│   ├── pipeline.md
│   ├── Technical specifications for creating an application IT-Tools .txt
│   └── Парметры базы данных MySQL на hoster.kz для IT-Tools .txt
├── models/
│   ├── Database.php
│   ├── Lesson.php
│   └── Section.php
├── public/
│   ├── css/
│   │   └── styles.css
│   ├── images/
│   │   ├── apple-touch-icon.png
│   │   ├── favicon.ico
│   │   ├── icon-192.png
│   │   └── icon-512.png
│   ├── js/
│   │   ├── main.js
│   │   └── tests.js
│   ├── manifest.webmanifest
│   └── vendor/
│       └── ckeditor/
│           ├── ckeditor.js
│           ├── classic/
│           ├── super/
│           └── translations/
├── sql/
│   ├── schema.sql
│   └── test_data.sql
└── templates/
    ├── 404.php
    ├── admin/
    │   ├── dashboard.php
    │   ├── lesson_form.php
    │   ├── login.php
    │   └── section_form.php
    ├── footer.php
    ├── header.php
    ├── home.php
    └── lesson.php
```

## Описание директорий и файлов

### Корневые файлы
- **.htaccess** - Конфигурация Apache для перенаправления всех запросов на index.php
- **index.php** - Front Controller, точка входа для всех HTTP-запросов

### Директория docs/
- **structure.md** - Файл со структурой проекта (этот файл)
- **description.md** - Текстовое описание проекта
- **pipeline.md** - Последовательность создания проекта
- **Technical specifications for creating an application IT-Tools .txt** - Исходное техническое задание
- **Парметры базы данных MySQL на hoster.kz для IT-Tools .txt** - Параметры подключения к базе данных на хостинге

### Директория config/
- **database.php** - Файл подключения к базе данных MySQL с hardcode credential'ами

### Директория public/
- **css/styles.css** - Основной файл стилей (CSS по методологии БЭМ, OKLCH цвета, Mobile First)
- **js/main.js** - Основной JavaScript файл для интерактивности
- **js/tests.js** - JavaScript для функционала тестов
- **images/** - Статические изображения (favicon, иконки для PWA)
  - **apple-touch-icon.png** - Иконка для Apple устройств
  - **favicon.ico** - Favicon для браузера
  - **icon-192.png** - Иконка PWA 192x192
  - **icon-512.png** - Иконка PWA 512x512
- **manifest.webmanifest** - Манифест PWA приложения
- **vendor/ckeditor/** - CKEditor 5 редактор для админ-панели
  - **ckeditor.js** - Основной файл CKEditor
  - **classic/** - Классическая сборка редактора
  - **super/** - Супер сборка редактора
  - **translations/** - Переводы редактора

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

### Директория controllers/
- **HomeController.php** - Контроллер для главной страницы
- **LessonController.php** - Контроллер для отображения уроков
- **AdminController.php** - Контроллер для админ-панели (авторизация, CRUD)

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