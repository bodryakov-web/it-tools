<?php
/**
 * Шаблон шапки сайта
 * Содержит адаптивный header с логотипом, навигацией и переключением темы
 * 
 * @author IT-Tools Project
 * @version 1.0
 */
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT-Tools - Учебный курс по IT-инструментам для бизнеса</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="/it-tools/public/images/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="/it-tools/public/images/apple-touch-icon.png">
    <link rel="manifest" href="/it-tools/public/manifest.webmanifest">
    <link rel="stylesheet" href="/it-tools/public/css/styles.css">
</head>
<body>
    <header class="header">
        <div class="header__container">
            <div class="header__logo">
                <a href="/it-tools/" class="header__logo-link">IT-Tools</a>
            </div>
            <div class="header__center">
                <h4 class="header__center-text header__center-text--short">IT инструменты для бизнеса</span>
                <h4 class="header__center-text header__center-text--long">IT инструменты для бизнеса (анализ данных, визуализация, боты, программирование без кода, автоматизация, ИИ)</span>
            </div>
            <div class="header__theme-toggle">
                <button class="theme-toggle" data-action="toggle-theme" aria-label="Переключить тему">
                    <span class="theme-toggle__icon" data-theme-icon="sun">☀️</span>
                    <span class="theme-toggle__icon" data-theme-icon="moon">🌙</span>
                </button>
            </div>
        </div>
    </header>
    <main class="main">
