<?php
/**
 * Форма создания/редактирования раздела
 * 
 * @author IT-Tools Project
 * @version 1.0
 */

$isEdit = isset($section) && $section;
$title = $isEdit ? 'Редактирование раздела' : 'Создание раздела';
$formAction = $isEdit ? '/it-tools/bod/update-section' : '/it-tools/bod/store-section';
$sectionTitle = $isEdit ? htmlspecialchars($section['title_ru']) : '';
$sectionSlug = $isEdit ? htmlspecialchars($section['slug']) : '';
$sectionOrder = $isEdit ? $section['section_order'] : '';
$sectionId = $isEdit ? $section['id'] : '';
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
                <input type="hidden" name="id" value="<?php echo $sectionId; ?>">
            <?php endif; ?>
            
            <div class="admin-form__field">
                <label class="admin-form__label" for="title_ru">Название раздела *</label>
                <input class="admin-form__input" type="text" id="title_ru" name="title_ru" 
                       value="<?php echo $sectionTitle; ?>" required>
                <small class="admin-form__hint">Название на русском языке</small>
            </div>
            
            <div class="admin-form__field">
                <label class="admin-form__label" for="slug">Slug *</label>
                <input class="admin-form__input" type="text" id="slug" name="slug" 
                       value="<?php echo $sectionSlug; ?>" required
                       pattern="^[a-z-]+$" title="Только маленькие английские буквы и дефисы">
                <small class="admin-form__hint">Только маленькие английские буквы и дефисы</small>
            </div>
            
            <div class="admin-form__field">
                <label class="admin-form__label" for="section_order">Порядковый номер *</label>
                <input class="admin-form__input" type="number" id="section_order" name="section_order" 
                       value="<?php echo $sectionOrder; ?>" required min="1">
                <small class="admin-form__hint">Число для формирования URL</small>
            </div>
            
            <div class="admin-form__actions">
                <button class="admin-button admin-button--primary" type="submit">
                    <?php echo $isEdit ? 'Сохранить изменения' : 'Создать раздел'; ?>
                </button>
                <a href="/it-tools/bod/dashboard" class="admin-button admin-button--danger">Отмена</a>
            </div>
        </form>
    </div>
</div>