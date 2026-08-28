<?php
/**
 * Шаблон страницы урока
 * Отображает урок с тремя секциями: теория, тесты, лабораторная работа
 * 
 * @author IT-Tools Project
 * @version 1.0
 */
?>

<div class="lesson">
    <div class="lesson__container">
        <!-- Хлебные крошки (навигация) -->
        <div class="lesson__breadcrumbs">
            <a href="/it-tools/" class="lesson__breadcrumb" data-action="go-home">Оглавление</a>
            <span class="lesson__breadcrumb-separator">/</span>
            <span class="lesson__breadcrumb-current"><?php echo htmlspecialchars($lesson['title_ru']); ?></span>
        </div>
        
        <!-- Заголовок урока -->
        <h1 class="lesson__title"><?php echo htmlspecialchars($lesson['title_ru']); ?></h1>
        
        <!-- Секция: Теоретический материал -->
        <section class="lesson__section lesson__section--theory">
            <div class="lesson__content">
                <?php echo $lesson['content']['theory']; ?>
            </div>
        </section>
        
        <!-- Секция: Тестирование -->
        <?php if (!empty($lesson['content']['tests'])): ?>
        <section class="lesson__section lesson__section--tests">
            <h2 class="lesson__section-title">Тестирование</h2>
            <div class="tests">
                <?php foreach ($lesson['content']['tests'] as $testIndex => $test): ?>
                    <div class="test-question" data-test-question="<?php echo $testIndex; ?>">
                        <h3 class="test-question__title">
                            <span class="test-question__number"><?php echo $testIndex + 1; ?>.</span>
                            <?php echo htmlspecialchars($test['question']); ?>
                        </h3>
                        <div class="test-question__answers">
                            <?php foreach ($test['answers'] as $answerIndex => $answer): ?>
                                <button class="test-answer" 
                                        data-test-question="<?php echo $testIndex; ?>" 
                                        data-test-answer="<?php echo $answerIndex; ?>"
                                        data-correct="<?php echo $test['correct']; ?>">
                                    <span class="test-answer__marker"></span>
                                    <span class="test-answer__text"><?php echo htmlspecialchars($answer); ?></span>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
        
        <!-- Секция: Лабораторная работа -->
        <?php if (!empty($lesson['content']['lab'])): ?>
        <section class="lesson__section lesson__section--lab">
            <h2 class="lesson__section-title">Лабораторная работа</h2>
            <div class="lesson__content">
                <?php echo $lesson['content']['lab']; ?>
            </div>
        </section>
        <?php endif; ?>
        
        <!-- Навигация между уроками -->
        <div class="lesson__navigation">
            <?php if ($prevUrl): ?>
                <a href="<?php echo $prevUrl; ?>" class="lesson__nav-button lesson__nav-button--prev">
                    ← Предыдущий урок
                </a>
            <?php endif; ?>
            
            <a href="/it-tools/" class="lesson__nav-button lesson__nav-button--home">
                Оглавление
            </a>
            
            <?php if ($nextUrl): ?>
                <a href="<?php echo $nextUrl; ?>" class="lesson__nav-button lesson__nav-button--next">
                    Следующий урок →
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="/it-tools/public/js/tests.js"></script>