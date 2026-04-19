<?php
$currentPath = $_SERVER['REQUEST_URI'] ?? '';
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Создание сотрудника</title>
    <!-- Подключаем общие стили -->
    <link rel="stylesheet" href="<?= app()->route->getUrl('/assets/style/main.css') ?>">
    <!-- Если есть специфичные стили для этой формы, подключите их здесь -->
    <link rel="stylesheet" href="<?= app()->route->getUrl('/assets/style/add_employee.css') ?>">
</head>

<body>

<main class="content-wrapper">
    <div class="content-container">
        <div class="page-title-box">
            <h1>Создание сотрудника</h1>
        </div>

        <div class="form-container">
            <form action="<?= app()->route->getUrl('/create_user') ?>" method="POST">
                <div class="form-grid">
                    <label for="login" class="form-label">Логин</label>
                    <div class="form-input-wrapper">
                        <input type="text" id="login" name="login" placeholder="Введите логин" required>
                    </div>
                    <div class="empty-cell"></div>

                    <label for="fio" class="form-label">ФИО</label>
                    <div class="form-input-wrapper">
                        <input type="text" id="fio" name="full_name" placeholder="Введите ФИО" required>
                    </div>
                    <div class="empty-cell"></div>

                    <label for="role" class="form-label">Роль</label>
                    <div class="form-input-wrapper select-wrapper">
                        <select id="role" name="role" required>
                            <option value="2">Сотрудник</option>
                            <option value="1">Администратор</option>
                        </select>
                    </div>
                    <div class="empty-cell"></div>

                    <label for="password" class="form-label">Пароль</label>
                    <div class="form-input-wrapper">
                        <input type="password" id="password" name="password" placeholder="Введите пароль" required>
                    </div>
                    <div class="button-wrapper">
                        <button type="submit" class="confirm-btn">Подтвердить</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

</body>
</html>