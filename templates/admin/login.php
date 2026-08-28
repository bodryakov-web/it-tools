<?php
/**
 * Форма входа в админ-панель
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
    <title>Вход в админ-панель - IT-Tools</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/it-tools/public/css/styles.css">
</head>
<body>
    <div class="admin-login">
        <div class="admin-login__container">
            <h1 class="admin-login__title">Вход в админ-панель</h1>
            
            <?php if (isset($error)): ?>
                <div class="admin-login__error">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <form class="admin-login__form" action="/it-tools/bod/authenticate" method="post">
                <div class="admin-login__field">
                    <label class="admin-login__label" for="login">Логин</label>
                    <input class="admin-login__input" type="text" id="login" name="login" required>
                </div>
                
                <div class="admin-login__field">
                    <label class="admin-login__label" for="password">Пароль</label>
                    <input class="admin-login__input" type="password" id="password" name="password" required>
                </div>
                
                <button class="admin-login__button" type="submit">Войти</button>
            </form>
        </div>
    </div>
</body>
</html>