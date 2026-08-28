<?php
/**
 * Страница ошибки 404
 * Отображается при обращении к несуществующему URL
 * 
 * @author IT-Tools Project
 * @version 1.0
 */

require_once TEMPLATES_PATH . '/header.php';
?>

<div class="error-page">
    <div class="error-page__container">
        <h1 class="error-page__title">404</h1>
        <p class="error-page__message">Страница не найдена</p>
        <p class="error-page__description">Запрошенная страница не существует или была перемещена.</p>
        <a href="/it-tools/" class="error-page__button" data-action="go-home">На главную</a>
    </div>
</div>

<?php require_once TEMPLATES_PATH . '/footer.php'; ?>