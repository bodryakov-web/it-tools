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
                <div class="editor-container">
                    <textarea class="admin-form__editor" id="theory" name="theory" rows="10"><?php echo $theoryContent; ?></textarea>
                </div>
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
                <div class="editor-container">
                    <textarea class="admin-form__editor" id="lab" name="lab" rows="10"><?php echo $labContent; ?></textarea>
                </div>
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

<!-- CKEditor 5 Classic local build -->
<script src="/it-tools/public/vendor/ckeditor/classic/ckeditor.js"></script>
<script src="/it-tools/public/vendor/ckeditor/classic/ru.js"></script>
<script>
// Helper function to wrap selected text in code tags
function wrapInCode(fieldId) {
    const editor = window.editors && window.editors[fieldId];
    if (!editor) {
        return;
    }

    const selection = editor.model.document.selection;
    if (selection.isCollapsed) {
        alert('Please select text first');
        return;
    }

    try {
        editor.model.change(writer => {
            const ranges = selection.getRanges();
            for (const range of ranges) {
                // Set an attribute on the selected text
                writer.setAttribute('codeInline', true, range);
            }
        });
    } catch (error) {
        console.error('Error applying code formatting:', error);
    }
}

// Store editors globally for access
window.editors = {};

document.addEventListener('DOMContentLoaded', function() {
    const Editor = window.ClassicEditor;

    if (!Editor) {
        console.error('Local CKEditor ClassicEditor is not available');
        return;
    }

    const editorConfig = {
        toolbar: [
            'bold',
            '|',
            'insertTable',
            '|',
            'link'
        ],
        language: 'ru'
    };

    function initializeEditor(elementSelector, fieldName) {
        const textarea = document.querySelector(elementSelector);
        const initialData = textarea.value;
        
        Editor
            .create(textarea, editorConfig)
            .then(editor => {
                const toolbarElement = editor.ui.view.toolbar.element;
                if (toolbarElement) {
                    const codeButton = document.createElement('button');
                    codeButton.type = 'button';
                    codeButton.className = 'ck ck-button ck-button_with-text inline-code-btn';
                    codeButton.setAttribute('aria-label', 'Code');
                    codeButton.setAttribute('title', 'Code');
                    codeButton.textContent = '<code>';
                    codeButton.addEventListener('click', () => wrapInCode(fieldName));

                    const itemsWrapper = toolbarElement.querySelector('.ck-toolbar__items');
                    if (itemsWrapper) {
                        itemsWrapper.prepend(codeButton);
                    } else {
                        toolbarElement.prepend(codeButton);
                    }
                }

                // Register the codeInline attribute
                try {
                    editor.model.schema.extend('$text', { allowAttributes: 'codeInline' });
                    
                    // Add conversion from model to view (downcast)
                    editor.conversion.attributeToElement({
                        model: 'codeInline',
                        view: {
                            name: 'code'
                        }
                    });
                    
                    // Add conversion from view to model (upcast)
                    editor.conversion.for('upcast').elementToAttribute({
                        view: {
                            name: 'code'
                        },
                        model: {
                            key: 'codeInline',
                            value: true
                        }
                    });
                    
                    // Load initial data from textarea
                    if (initialData) {
                        editor.setData(initialData);
                    }
                } catch (e) {
                    console.warn('Could not register codeInline attribute:', e);
                }
                
                window.editors[fieldName] = editor;
                console.log(fieldName + ' editor initialized');
            })
            .catch(error => {
                console.error(fieldName + ' editor error:', error);
            });
    }

    // Initialize both editors
    initializeEditor('#theory', 'theory');
    initializeEditor('#lab', 'lab');
    
    // Sync editor data to textarea before form submission
    const form = document.querySelector('.admin-form__form');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Sync theory editor
            if (window.editors['theory']) {
                const theoryTextarea = document.querySelector('#theory');
                theoryTextarea.value = window.editors['theory'].getData();
            }
            
            // Sync lab editor
            if (window.editors['lab']) {
                const labTextarea = document.querySelector('#lab');
                labTextarea.value = window.editors['lab'].getData();
            }
        });
    }
});
</script>
<style>
/* Inline code styling */
.ck-content code,
code {
    background-color: #f5f5f5;
    padding: 0.2em 0.4em;
    border-radius: 3px;
    font-family: "Courier New", "Consolas", monospace;
    font-size: clamp(1.3rem, 1.1rem + 0.7vw, 2rem);
}

/* Make newly inserted CKEditor tables compact by default */
.ck-content figure.table {
    float: none !important;
    width: max-content !important;
    max-width: none !important;
    margin: 0 !important;
    margin-left: 0 !important;
    margin-right: auto !important;
    text-align: left !important;
}

.ck-content figure.table > table,
.ck-content table {
    border-collapse: collapse;
    table-layout: auto;
    float: none !important;
    width: max-content !important;
    max-width: none !important;
    margin: 0 !important;
    margin-left: 0 !important;
    margin-right: auto !important;
    text-align: left !important;
}

.ck-content figure.table > table td,
.ck-content figure.table > table th,
.ck-content table td,
.ck-content table th {
    vertical-align: top;
    min-width: 0 !important;
    width: 1em !important;
    white-space: nowrap !important;
}

.ck-content figure.table > table td p,
.ck-content figure.table > table th p,
.ck-content table td p,
.ck-content table th p {
    margin: 0;
    min-height: 0 !important;
    line-height: 1 !important;
}

.ck-content figure.table > table td > p:first-child:only-child:empty::before,
.ck-content figure.table > table th > p:first-child:only-child:empty::before,
.ck-content table td > p:first-child:only-child:empty::before,
.ck-content table th > p:first-child:only-child:empty::before {
    content: "\200b";
}

/* Dark theme for code */
[data-theme="dark"] .ck-content code,
[data-theme="dark"] code {
    background-color: #2a2a2a;
    color: #e0e0e0;
}

/* Editor container for positioning */
.editor-container {
    position: relative;
}

/* Inline code button styling */
.inline-code-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 auto;
    margin-right: var(--spacing-sm);
    gap: 0;
    padding: var(--ck-spacing-tiny) var(--ck-spacing-standard);
    border: 1px solid var(--ck-color-base-border);
    border-radius: var(--ck-border-radius);
    background: var(--ck-color-button-default-background);
    color: var(--ck-color-button-default-text);
    cursor: pointer;
    font: inherit;
    line-height: 1.1;
    white-space: nowrap;
    box-shadow: none;
    appearance: none;
}

.inline-code-btn:hover {
    background: var(--ck-color-button-default-hover-background);
    color: var(--ck-color-button-default-hover-text);
}

.inline-code-btn:active {
    background: var(--ck-color-button-default-active-background);
    color: var(--ck-color-button-default-active-text);
}

.inline-code-btn:focus {
    outline: none;
    box-shadow: var(--ck-focus-outer-shadow), var(--ck-focus-inner-shadow);
}
</style>
