<?php
/**
 * Шаблон главной страницы
 * Отображает разделы в виде карточек с уроками
 * 
 * @author IT-Tools Project
 * @version 1.0
 */
?>

<div class="home">
    <div class="home__container">
        <h1 class="home__title">Учебный курс IT-Tools</h1>
        <p class="home__subtitle">IT инструменты для бизнеса — от основ до практического применения</p>
        
        <div class="sections">
            <?php if (empty($sectionsWithLessons)): ?>
                <div class="sections__empty">
                    <p class="sections__empty-text">Разделы пока не созданы</p>
                </div>
            <?php else: ?>
                <?php foreach ($sectionsWithLessons as $section): ?>
                    <div class="section-card">
                        <h2 class="section-card__title">
                            <?php echo htmlspecialchars($section['title_ru']); ?>
                        </h2>
                        
                        <?php if (empty($section['lessons'])): ?>
                            <p class="section-card__empty">В этом разделе пока нет уроков</p>
                        <?php else: ?>
                            <div class="section-card__lessons">
                                <?php foreach ($section['lessons'] as $lesson): ?>
                                    <a href="/it-tools/<?php echo $section['section_order']; ?>-<?php echo htmlspecialchars($section['slug']); ?>/<?php echo $lesson['lesson_order']; ?>-<?php echo htmlspecialchars($lesson['slug']); ?>" 
                                       class="lesson-button"
                                       data-lesson-id="<?php echo $lesson['id']; ?>">
                                        <span class="lesson-button__title"><?php echo htmlspecialchars($lesson['title_ru']); ?></span>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>