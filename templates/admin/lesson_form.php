<?php
/**
 * Форма создания/редактирования урока с CKEditor 5
 * 
 * @author IT-Tools Project
 * @version 1.0
 */

$isEdit = isset($lesson) && $lesson;
$title = $isEdit ? 'Редактирование урока' : 'Создание урока';
$formAction = $isEdit ? '/it-tools/bod/update-lesson' : '/it-tools/bod/store-lesson';

$lessonTitle = $isEdit ? htmlspecialchars($lesson['title_ru']) : '';
$lessonSlug = $isEdit ? htmlspecialchars($lesson['slug']) : '';
$lessonOrder = $isEdit ? $lesson['lesson_order'] : '';
$sectionId = $isEdit ? $lesson['section_id'] : '';
$lessonId = $isEdit ? $lesson['id'] : '';

$theoryContent = $isEdit ? $lesson['content']['theory'] : '';
$labContent = $isEdit ? $lesson['content']['lab'] : '';
$testsText = isset($testsText) ? $testsText : '';
?>

<div class="admin-form">
    <div class="admin-form__container">
        <h1 class="admin-form__title"><?php echo $title; ?></h1>
        
        <?php if (!empty($errors)): ?>
            <div class="admin-form__errors">
                <?php foreach ($errors as $error): ?>
                    <div class="admin-form__error"><?php echo htmlspecialchars($error); ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <form class="admin-form__form" action="<?php echo $formAction; ?>" method="post">
            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?php echo $lessonId; ?>">
            <?php endif; ?>
            
            <div class="admin-form__field">
                <label class="admin-form__label" for="section_id">Раздел *</label>
                <select class="admin-form__select" id="section_id" name="section_id" required>
                    <option value=""></option>
                    <?php foreach ($sections as $section): ?>
                        <option value="<?php echo $section['id']; ?>" 
                                <?php echo $sectionId == $section['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($section['title_ru']); ?> (<?php echo $section['section_order']; ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="admin-form__field">
                <label class="admin-form__label" for="title_ru">Название урока *</label>
                <input class="admin-form__input" type="text" id="title_ru" name="title_ru" 
                       value="<?php echo $lessonTitle; ?>" required>
            </div>
            
            <div class="admin-form__field">
                <label class="admin-form__label" for="slug">Slug *</label>
                <input class="admin-form__input" type="text" id="slug" name="slug" 
                       value="<?php echo $lessonSlug; ?>" required
                       pattern="^[a-z-]+$">
            </div>
            
            <div class="admin-form__field">
                <label class="admin-form__label" for="lesson_order">Порядковый номер *</label>
                <input class="admin-form__input" type="number" id="lesson_order" name="lesson_order" 
                       value="<?php echo $lessonOrder; ?>" required min="1">
            </div>
            
            <div class="admin-form__field">
                <label class="admin-form__label" for="theory">Теоретический материал</label>
                <textarea class="admin-form__editor" id="theory" name="theory" rows="10"><?php echo $theoryContent; ?></textarea>
            </div>
            
            <div class="admin-form__field">
                <label class="admin-form__label" for="tests_text">Тесты</label>
                <textarea class="admin-form__textarea" id="tests_text" name="tests_text" rows="10" 
                          placeholder="Вопрос?
Ответ 1
Ответ 2 ✔
Ответ 3
Ответ 4

Вопрос 2?
..."><?php echo $testsText; ?></textarea>
            </div>
            
            <div class="admin-form__field">
                <label class="admin-form__label" for="lab">Лабораторная работа</label>
                <textarea class="admin-form__editor" id="lab" name="lab" rows="10"><?php echo $labContent; ?></textarea>
            </div>
            
            <div class="admin-form__actions">
                <button class="admin-button admin-button--primary" type="submit">
                    <?php echo $isEdit ? 'Сохранить изменения' : 'Опубликовать урок'; ?>
                </button>
                <a href="/it-tools/bod/dashboard" class="admin-button admin-button--danger">Отмена</a>
            </div>
        </form>
    </div>
</div>

<!-- CKEditor 5 CDN -->
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Инициализация CKEditor 5 для теории
    ClassicEditor
        .create(document.querySelector('#theory'), {
            toolbar: [
                'codeBlock', '|',
                'removeFormat', '|',
                'code', '|',
                'bold', '|',
                'fontSize:small', '|',
                'insertTable', '|',
                'link'
            ],
            language: 'ru'
        })
        .catch(error => {
            console.error(error);
        });
    
    // Инициализация CKEditor 5 для лабораторной работы
    ClassicEditor
        .create(document.querySelector('#lab'), {
            toolbar: [
                'codeBlock', '|',
                'removeFormat', '|',
                'code', '|',
                'bold', '|',
                'fontSize:small', '|',
                'insertTable', '|',
                'link'
            ],
            language: 'ru'
        })
        .catch(error => {
            console.error(error);
        });
});
</script>