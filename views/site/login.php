<?php
$message = $_SESSION['message'] ?? '';
if ($message) unset($_SESSION['message']);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация | Учебно-методическое управление</title>
    <link rel="stylesheet" href="assets/style/login.css">
</head>
<body>

<div class="login-card">
    <h2>Авторизация</h2>

    <?php if (!empty($message)): ?>
        <div class="alert-message" style="color: red; margin-bottom: 15px; text-align: center;">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <?php if (!app()->auth->check()): ?>
        <form method="POST" action="<?= app()->route->getUrl('/login') ?>">
            <!--<input name="csrf_token" type="hidden" value="<?= app()->auth->generateCSRF() ?>"/>-->
            <div class="form-group">
                <input type="text" name="login" placeholder="Логин" required>
            </div>

            <div class="form-group">
                <input type="password" name="password" placeholder="Пароль" required>
            </div>

            <button type="submit" class="btn-submit">Войти</button>
        </form>
    <?php else: ?>
        <p style="text-align: center;">Вы уже вошли как: <strong><?= htmlspecialchars(app()->auth->user()->name ?? app()->auth->user()->login) ?></strong></p>
        <div style="text-align: center; margin-top: 10px;">
             <a href="<?= app()->route->getUrl('/logout') ?>" class="btn-submit" style="text-decoration: none; display: inline-block;">Выйти</a>
        </div>
    <?php endif; ?>
</div>

</body>
</html>