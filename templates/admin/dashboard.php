<?php
/**
 * Главная страница админ-панели (dashboard)
 * Отображает списки разделов и уроков с кнопками управления
 * 
 * @author IT-Tools Project
 * @version 1.0
 */
?>

<div class="admin-dashboard">
    <div class="admin-dashboard__container">
        <div class="admin-dashboard__header">
            <h1 class="admin-dashboard__title">Админ-панель</h1>
            <div class="admin-dashboard__actions">
                <a href="/it-tools/bod/create-section" class="admin-button admin-button--primary">Добавить раздел</a>
                <a href="/it-tools/bod/create-lesson" class="admin-button admin-button--primary">Добавить урок</a>
                <a href="/it-tools/bod/logout" class="admin-button admin-button--danger">Выйти</a>
            </div>
        </div>
        
        <?php if (isset($error)): ?>
            <div class="admin-dashboard__error">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <div class="admin-dashboard__sections">
            <?php if (empty($sectionsWithLessons)): ?>
                <div class="admin-dashboard__empty">
                    <p>Разделы пока не созданы</p>
                </div>
            <?php else: ?>
                <?php foreach ($sectionsWithLessons as $section): ?>
                    <div class="admin-section-card">
                        <div class="admin-section-card__header">
                            <h2 class="admin-section-card__title">
                                <?php echo htmlspecialchars($section['title_ru']); ?>
                                <span class="admin-section-card__order">(<?php echo $section['section_order']; ?>)</span>
                            </h2>
                            <div class="admin-section-card__actions">
                                <a href="/it-tools/bod/edit-section?id=<?php echo $section['id']; ?>" 
                                   class="admin-button admin-button--small"
                                   title="Редактировать раздел">✎</a>
                                <a href="/it-tools/bod/delete-section?id=<?php echo $section['id']; ?>" 
                                   class="admin-button admin-button--small admin-button--danger"
                                   title="Удалить раздел"
                                   onclick="return confirm('Удалить раздел и все уроки в нём?');">✖</a>
                            </div>
                        </div>
                        
                        <div class="admin-section-card__info">
                            <span class="admin-section-card__slug">Slug: <?php echo htmlspecialchars($section['slug']); ?></span>
                        </div>
                        
                        <?php if (empty($section['lessons'])): ?>
                            <p class="admin-section-card__empty">В этом разделе пока нет уроков</p>
                        <?php else: ?>
                            <div class="admin-section-card__lessons">
                                <?php foreach ($section['lessons'] as $lesson): ?>
                                    <div class="admin-lesson-item">
                                        <div class="admin-lesson-item__info">
                                            <span class="admin-lesson-item__order"><?php echo $lesson['lesson_order']; ?>.</span>
                                            <span class="admin-lesson-item__title"><?php echo htmlspecialchars($lesson['title_ru']); ?></span>
                                            <span class="admin-lesson-item__slug">(<?php echo htmlspecialchars($lesson['slug']); ?>)</span>
                                        </div>
                                        <div class="admin-lesson-item__actions">
                                            <a href="/it-tools/bod/edit-lesson?id=<?php echo $lesson['id']; ?>" 
                                               class="admin-button admin-button--small"
                                               title="Редактировать урок">✎</a>
                                            <a href="/it-tools/bod/delete-lesson?id=<?php echo $lesson['id']; ?>" 
                                               class="admin-button admin-button--small admin-button--danger"
                                               title="Удалить урок"
                                               onclick="return confirm('Удалить урок?');">✖</a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>