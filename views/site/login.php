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

    <form method="POST" action="<?= app()->route->getUrl('/login') ?>">
        <div class="form-group">
            <input type="text" name="login" placeholder="Логин" required>
        </div>

        <div class="form-group">
            <input type="password" name="password" placeholder="Пароль" required>
        </div>

        <button type="submit" class="btn-submit">Войти</button>
    </form>
</div>

</body>
</html>