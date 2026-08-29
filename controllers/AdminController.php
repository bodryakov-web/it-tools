<?php
/**
 * Контроллер админ-панели
 * Управляет авторизацией и CRUD операциями для разделов и уроков
 * 
 * @author IT-Tools Project
 * @version 1.0
 */

class AdminController {
    private $sectionModel;
    private $lessonModel;
    
    /**
     * Конструктор - инициализирует модели и проверяет авторизацию
     */
    public function __construct() {
        $db = new Database();
        $this->sectionModel = new Section($db);
        $this->lessonModel = new Lesson($db);
    }
    
    /**
     * Отображает форму входа в админ-панель
     */
    public function login() {
        // Если уже авторизован, перенаправляем на dashboard
        if ($this->isAdmin()) {
            header('Location: /it-tools/bod/dashboard');
            exit;
        }
        
        require_once TEMPLATES_PATH . '/admin/login.php';
    }
    
    /**
     * Обрабатывает форму входа
     */
    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $login = $_POST['login'] ?? '';
            $password = $_POST['password'] ?? '';
            
            if ($login === ADMIN_LOGIN && $password === ADMIN_PASSWORD) {
                $_SESSION['is_admin'] = true;
                header('Location: /it-tools/bod/dashboard');
                exit;
            } else {
                $error = 'Неверный логин или пароль';
                require_once TEMPLATES_PATH . '/admin/login.php';
            }
        } else {
            header('Location: /it-tools/bod/login');
            exit;
        }
    }
    
    /**
     * Выход из админ-панели
     */
    public function logout() {
        session_destroy();
        header('Location: /it-tools/bod/login');
        exit;
    }
    
    /**
     * Отображает главную страницу админ-панели (dashboard)
     */
    public function dashboard() {
        $this->checkAuth();
        
        $sections = $this->sectionModel->getAll();
        
        // Получаем уроки для каждого раздела
        $sectionsWithLessons = [];
        foreach ($sections as $section) {
            $lessons = $this->lessonModel->getBySectionId($section['id']);
            $section['lessons'] = $lessons;
            $sectionsWithLessons[] = $section;
        }
        
        require_once TEMPLATES_PATH . '/header.php';
        require_once TEMPLATES_PATH . '/admin/dashboard.php';
        require_once TEMPLATES_PATH . '/footer.php';
    }
    
    /**
     * Отображает форму создания раздела
     */
    public function createSection() {
        $this->checkAuth();
        require_once TEMPLATES_PATH . '/header.php';
        require_once TEMPLATES_PATH . '/admin/section_form.php';
        require_once TEMPLATES_PATH . '/footer.php';
    }
    
    /**
     * Обрабатывает создание раздела
     */
    public function storeSection() {
        $this->checkAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title_ru'] ?? '');
            $slug = trim($_POST['slug'] ?? '');
            $order = intval($_POST['section_order'] ?? 0);
            
            // Валидация
            $errors = [];
            
            if (empty($title)) {
                $errors[] = 'Название раздела обязательно для заполнения';
            }
            
            if (empty($slug)) {
                $errors[] = 'Slug обязателен для заполнения';
            } elseif (!$this->sectionModel->validateSlugFormat($slug)) {
                $errors[] = 'Slug может содержать только маленькие английские буквы и дефисы';
            } elseif (!$this->sectionModel->isSlugUnique($slug)) {
                $errors[] = 'Slug уже используется';
            }
            
            if (empty($order) || $order < 1) {
                $errors[] = 'Порядковый номер должен быть положительным числом';
            } elseif (!$this->sectionModel->isOrderUnique($order)) {
                $errors[] = 'Порядковый номер уже используется';
            }
            
            if (!$this->sectionModel->isTitleUnique($title)) {
                $errors[] = 'Название уже используется';
            }
            
            if (empty($errors)) {
                $data = [
                    'title_ru' => $title,
                    'slug' => $slug,
                    'section_order' => $order
                ];
                
                if ($this->sectionModel->create($data)) {
                    header('Location: /it-tools/bod/dashboard');
                    exit;
                } else {
                    $errors[] = 'Ошибка при создании раздела';
                }
            }
            
            // Если есть ошибки, выводим форму с ошибками
            require_once TEMPLATES_PATH . '/header.php';
            require_once TEMPLATES_PATH . '/admin/section_form.php';
            require_once TEMPLATES_PATH . '/footer.php';
        } else {
            header('Location: /it-tools/bod/create-section');
            exit;
        }
    }
    
    /**
     * Отображает форму редактирования раздела
     */
    public function editSection() {
        $this->checkAuth();
        
        $id = intval($_GET['id'] ?? 0);
        $section = $this->sectionModel->getById($id);
        
        if (!$section) {
            header('Location: /it-tools/bod/dashboard');
            exit;
        }
        
        require_once TEMPLATES_PATH . '/header.php';
        require_once TEMPLATES_PATH . '/admin/section_form.php';
        require_once TEMPLATES_PATH . '/footer.php';
    }
    
    /**
     * Обрабатывает обновление раздела
     */
    public function updateSection() {
        $this->checkAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id'] ?? 0);
            $title = trim($_POST['title_ru'] ?? '');
            $slug = trim($_POST['slug'] ?? '');
            $order = intval($_POST['section_order'] ?? 0);
            
            // Валидация
            $errors = [];
            
            if (empty($title)) {
                $errors[] = 'Название раздела обязательно для заполнения';
            }
            
            if (empty($slug)) {
                $errors[] = 'Slug обязателен для заполнения';
            } elseif (!$this->sectionModel->validateSlugFormat($slug)) {
                $errors[] = 'Slug может содержать только маленькие английские буквы и дефисы';
            } elseif (!$this->sectionModel->isSlugUnique($slug, $id)) {
                $errors[] = 'Slug уже используется';
            }
            
            if (empty($order) || $order < 1) {
                $errors[] = 'Порядковый номер должен быть положительным числом';
            } elseif (!$this->sectionModel->isOrderUnique($order, $id)) {
                $errors[] = 'Порядковый номер уже используется';
            }
            
            if (!$this->sectionModel->isTitleUnique($title, $id)) {
                $errors[] = 'Название уже используется';
            }
            
            if (empty($errors)) {
                $data = [
                    'title_ru' => $title,
                    'slug' => $slug,
                    'section_order' => $order
                ];
                
                if ($this->sectionModel->update($id, $data)) {
                    header('Location: /it-tools/bod/dashboard');
                    exit;
                } else {
                    $errors[] = 'Ошибка при обновлении раздела';
                }
            }
            
            // Если есть ошибки, выводим форму с ошибками
            $section = $this->sectionModel->getById($id);
            require_once TEMPLATES_PATH . '/header.php';
            require_once TEMPLATES_PATH . '/admin/section_form.php';
            require_once TEMPLATES_PATH . '/footer.php';
        } else {
            header('Location: /it-tools/bod/dashboard');
            exit;
        }
    }
    
    /**
     * Удаляет раздел
     */
    public function deleteSection() {
        $this->checkAuth();
        
        $id = intval($_GET['id'] ?? 0);
        
        if ($this->sectionModel->delete($id)) {
            header('Location: /it-tools/bod/dashboard');
            exit;
        } else {
            $error = 'Ошибка при удалении раздела';
            $sections = $this->sectionModel->getAll();
            require_once TEMPLATES_PATH . '/header.php';
            require_once TEMPLATES_PATH . '/admin/dashboard.php';
            require_once TEMPLATES_PATH . '/footer.php';
        }
    }
    
    /**
     * Отображает форму создания урока
     */
    public function createLesson() {
        $this->checkAuth();
        
        $sections = $this->sectionModel->getAll();
        require_once TEMPLATES_PATH . '/header.php';
        require_once TEMPLATES_PATH . '/admin/lesson_form.php';
        require_once TEMPLATES_PATH . '/footer.php';
    }
    
    /**
     * Обрабатывает создание урока
     */
    public function storeLesson() {
        $this->checkAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $sectionId = intval($_POST['section_id'] ?? 0);
            $title = trim($_POST['title_ru'] ?? '');
            $slug = trim($_POST['slug'] ?? '');
            $order = intval($_POST['lesson_order'] ?? 0);
            $theory = $_POST['theory'] ?? '';
            $lab = $_POST['lab'] ?? '';
            $testsText = $_POST['tests_text'] ?? '';
            
            // Обработка тестов
            $tests = $this->parseTestsFromText($testsText);
            
            // Формирование контента
            $content = [
                'theory' => $theory,
                'tests' => $tests,
                'lab' => $lab
            ];
            
            // Валидация
            $errors = [];
            
            if (empty($sectionId)) {
                $errors[] = 'Раздел обязателен для выбора';
            }
            
            if (empty($title)) {
                $errors[] = 'Название урока обязательно для заполнения';
            }
            
            if (empty($slug)) {
                $errors[] = 'Slug обязателен для заполнения';
            } elseif (!$this->lessonModel->validateSlugFormat($slug)) {
                $errors[] = 'Slug может содержать только маленькие английские буквы и дефисы';
            } elseif (!$this->lessonModel->isSlugUnique($sectionId, $slug)) {
                $errors[] = 'Slug уже используется в этом разделе';
            }
            
            if (empty($order) || $order < 1) {
                $errors[] = 'Порядковый номер должен быть положительным числом';
            } elseif (!$this->lessonModel->isOrderUnique($sectionId, $order)) {
                $errors[] = 'Порядковый номер уже используется в этом разделе';
            }
            
            if (!$this->lessonModel->isTitleUnique($sectionId, $title)) {
                $errors[] = 'Название уже используется в этом разделе';
            }
            
            if (empty($errors)) {
                $data = [
                    'section_id' => $sectionId,
                    'title_ru' => $title,
                    'slug' => $slug,
                    'lesson_order' => $order,
                    'content' => $content
                ];
                
                if ($this->lessonModel->create($data)) {
                    header('Location: /it-tools/bod/dashboard');
                    exit;
                } else {
                    $errors[] = 'Ошибка при создании урока';
                }
            }
            
            // Если есть ошибки, выводим форму с ошибками
            $sections = $this->sectionModel->getAll();
            require_once TEMPLATES_PATH . '/header.php';
            require_once TEMPLATES_PATH . '/admin/lesson_form.php';
            require_once TEMPLATES_PATH . '/footer.php';
        } else {
            header('Location: /it-tools/bod/create-lesson');
            exit;
        }
    }
    
    /**
     * Отображает форму редактирования урока
     */
    public function editLesson() {
        $this->checkAuth();
        
        $id = intval($_GET['id'] ?? 0);
        $lesson = $this->lessonModel->getById($id);
        
        if (!$lesson) {
            header('Location: /it-tools/bod/dashboard');
            exit;
        }
        
        // Преобразование тестов из JSON в текстовый формат
        $testsText = $this->convertTestsToJson($lesson['content']['tests'] ?? []);
        
        $sections = $this->sectionModel->getAll();
        
        require_once TEMPLATES_PATH . '/header.php';
        require_once TEMPLATES_PATH . '/admin/lesson_form.php';
        require_once TEMPLATES_PATH . '/footer.php';
    }
    
    /**
     * Обрабатывает обновление урока
     */
    public function updateLesson() {
        $this->checkAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id'] ?? 0);
            $sectionId = intval($_POST['section_id'] ?? 0);
            $title = trim($_POST['title_ru'] ?? '');
            $slug = trim($_POST['slug'] ?? '');
            $order = intval($_POST['lesson_order'] ?? 0);
            $theory = $_POST['theory'] ?? '';
            $lab = $_POST['lab'] ?? '';
            $testsText = $_POST['tests_text'] ?? '';
            
            // Обработка тестов
            $tests = $this->parseTestsFromText($testsText);
            
            // Формирование контента
            $content = [
                'theory' => $theory,
                'tests' => $tests,
                'lab' => $lab
            ];
            
            // Валидация
            $errors = [];
            
            if (empty($sectionId)) {
                $errors[] = 'Раздел обязателен для выбора';
            }
            
            if (empty($title)) {
                $errors[] = 'Название урока обязательно для заполнения';
            }
            
            if (empty($slug)) {
                $errors[] = 'Slug обязателен для заполнения';
            } elseif (!$this->lessonModel->validateSlugFormat($slug)) {
                $errors[] = 'Slug может содержать только маленькие английские буквы и дефисы';
            } elseif (!$this->lessonModel->isSlugUnique($sectionId, $slug, $id)) {
                $errors[] = 'Slug уже используется в этом разделе';
            }
            
            if (empty($order) || $order < 1) {
                $errors[] = 'Порядковый номер должен быть положительным числом';
            } elseif (!$this->lessonModel->isOrderUnique($sectionId, $order, $id)) {
                $errors[] = 'Порядковый номер уже используется в этом разделе';
            }
            
            if (!$this->lessonModel->isTitleUnique($sectionId, $title, $id)) {
                $errors[] = 'Название уже используется в этом разделе';
            }
            
            if (empty($errors)) {
                $data = [
                    'section_id' => $sectionId,
                    'title_ru' => $title,
                    'slug' => $slug,
                    'lesson_order' => $order,
                    'content' => $content
                ];
                
                if ($this->lessonModel->update($id, $data)) {
                    header('Location: /it-tools/bod/dashboard');
                    exit;
                } else {
                    $errors[] = 'Ошибка при обновлении урока: не удалось сохранить данные. Проверьте текст, HTML и спецсимволы.';
                }
            }
            
            // Если есть ошибки, выводим форму с ошибками
            $lesson = $this->lessonModel->getById($id);
            $testsText = $this->convertTestsToJson($lesson['content']['tests'] ?? []);
            $sections = $this->sectionModel->getAll();
            require_once TEMPLATES_PATH . '/header.php';
            require_once TEMPLATES_PATH . '/admin/lesson_form.php';
            require_once TEMPLATES_PATH . '/footer.php';
        } else {
            header('Location: /it-tools/bod/dashboard');
            exit;
        }
    }
    
    /**
     * Удаляет урок
     */
    public function deleteLesson() {
        $this->checkAuth();
        
        $id = intval($_GET['id'] ?? 0);
        
        if ($this->lessonModel->delete($id)) {
            header('Location: /it-tools/bod/dashboard');
            exit;
        } else {
            $error = 'Ошибка при удалении урока';
            $sections = $this->sectionModel->getAll();
            require_once TEMPLATES_PATH . '/header.php';
            require_once TEMPLATES_PATH . '/admin/dashboard.php';
            require_once TEMPLATES_PATH . '/footer.php';
        }
    }
    
    /**
     * Парсит тесты из текстового формата в JSON
     * 
     * @param string $text Текст с тестами
     * @return array Массив тестов в формате JSON
     */
    private function parseTestsFromText($text) {
        $tests = [];
        if (empty($text)) {
            return $tests;
        }
        
        // Разделение на блоки вопросов по пустым строкам
        $blocks = preg_split('/\n\s*\n/', trim($text));
        
        foreach ($blocks as $block) {
            $lines = array_filter(array_map('trim', explode("\n", $block)));
            
            if (count($lines) < 5) {
                continue; // Нужно минимум 5 строк (вопрос + 4 ответа)
            }
            
            $question = $lines[0];
            $answers = [];
            $correct = 0;
            
            // Проверка, что вопрос заканчивается на ?
            if (!str_ends_with($question, '?')) {
                continue;
            }
            
            // Обработка ответов
            for ($i = 1; $i <= 4; $i++) {
                if (isset($lines[$i])) {
                    $answer = $lines[$i];
                    // Проверка на галочку (✔) в конце строки
                    if (str_ends_with($answer, '✔')) {
                        $correct = $i - 1; // Индекс правильного ответа (0-3)
                        $answer = trim(substr($answer, 0, -1)); // Удаление галочки
                    }
                    $answers[] = $answer;
                }
            }
            
            if (count($answers) === 4) {
                $tests[] = [
                    'question' => $question,
                    'answers' => $answers,
                    'correct' => $correct
                ];
            }
        }
        
        return $tests;
    }
    
    /**
     * Преобразует тесты из JSON в текстовый формат
     * 
     * @param array $tests Массив тестов
     * @return string Текстовое представление тестов
     */
    private function convertTestsToJson($tests) {
        if (empty($tests)) {
            return '';
        }
        
        $text = '';
        foreach ($tests as $test) {
            $text .= $test['question'] . "\n";
            
            foreach ($test['answers'] as $index => $answer) {
                if ($index === $test['correct']) {
                    $text .= $answer . ' ✔' . "\n";
                } else {
                    $text .= $answer . "\n";
                }
            }
            
            $text .= "\n";
        }
        
        return trim($text);
    }
    
    /**
     * Проверяет авторизацию администратора
     */
    private function checkAuth() {
        if (!$this->isAdmin()) {
            header('Location: /it-tools/bod/login');
            exit;
        }
    }
    
    /**
     * Проверяет, авторизован ли пользователь как администратор
     * 
     * @return bool true если авторизован
     */
    private function isAdmin() {
        return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
    }
}