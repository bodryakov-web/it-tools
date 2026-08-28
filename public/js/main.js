/**
 * Основной JavaScript файл IT-Tools
 * Управляет интерактивностью, переключением темы и общими функциями
 * Использует data-атрибуты для выбора элементов
 * 
 * @author IT-Tools Project
 * @version 1.0
 */

document.addEventListener('DOMContentLoaded', function() {
    // Инициализация переключения темы
    initThemeToggle();
    
    // Инициализация навигации
    initNavigation();
});

/**
 * Инициализация переключения темы
 */
function initThemeToggle() {
    const themeToggle = document.querySelector('[data-action="toggle-theme"]');
    if (!themeToggle) return;
    
    // Загрузка сохранённой темы
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) {
        document.documentElement.setAttribute('data-theme', savedTheme);
    }
    
    // Обработка клика по кнопке переключения темы
    themeToggle.addEventListener('click', function() {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        
        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
    });
}

/**
 * Инициализация навигации
 */
function initNavigation() {
    // Обработка кнопки "На главную"
    const goHomeButton = document.querySelector('[data-action="go-home"]');
    if (goHomeButton) {
        goHomeButton.addEventListener('click', function(e) {
            // Позволяем стандартное поведение ссылки
            // Дополнительная логика может быть добавлена здесь
        });
    }
    
    // Обработка других навигационных элементов
    const navLinks = document.querySelectorAll('[data-nav]');
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const target = this.getAttribute('data-nav');
            if (target && target !== '#') {
                // Стандартная навигация
                // Дополнительная логика может быть добавлена здесь
            }
        });
    });
}

/**
 * Утилита для безопасного вывода HTML
 * Защищает от XSS атак при выводе пользовательского контента
 * 
 * @param {string} str - Строка для экранирования
 * @return {string} Экранированная строка
 */
function escapeHtml(str) {
    if (!str) return '';
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}

/**
 * Утилита для декодирования HTML-сущностей
 * Используется для блоков кода, где нужно отображать спецсимволы
 * 
 * @param {string} str - Строка с HTML-сущностями
 * @return {string} Декодированная строка
 */
function decodeHtmlEntities(str) {
    if (!str) return '';
    const div = document.createElement('div');
    div.innerHTML = str;
    return div.textContent;
}

/**
 * Утилита для форматирования даты
 * 
 * @param {Date|string} date - Дата для форматирования
 * @return {string} Отформатированная дата
 */
function formatDate(date) {
    if (!date) return '';
    const d = new Date(date);
    return d.toLocaleDateString('ru-RU', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

/**
 * Утилита для debounce функции
 * Ограничивает частоту вызова функции
 * 
 * @param {Function} func - Функция для debounce
 * @param {number} wait - Время ожидания в мс
 * @return {Function} Debounce-функция
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Утилита для проверки поддержки localStorage
 * 
 * @return {boolean} true если localStorage доступен
 */
function isLocalStorageAvailable() {
    try {
        const test = '__localStorage_test__';
        localStorage.setItem(test, test);
        localStorage.removeItem(test);
        return true;
    } catch (e) {
        return false;
    }
}