<?php
$currentPath = $_SERVER['REQUEST_URI'] ?? '';
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Создание сотрудника</title>
    <link rel="stylesheet" href="assets/style/add_employee.css">
</head>

<body>
<aside>
    <div>
        <div class="logo">
            Учебно-<br>методическое<br>управление
        </div>

        <nav>
            <?php if (!app()->auth::check()): ?>
                <a href="<?= app()->route->getUrl('/login') ?>"
                   class="<?= ($currentPath == '/login') ? 'active' : '' ?>">
                    Вход
                </a>
            <?php endif; ?>

            <?php if (app()->auth::check() && app()->auth::user()->isAdmin()): ?>
                <a href="<?= app()->route->getUrl('/admin_main') ?>"
                   class="<?= (strpos($currentPath, '/admin') !== false) ? 'active' : '' ?>">
                    Управление пользователями
                </a>
            <?php endif; ?>
        </nav>
    </div>

    <div class="sidebar-footer">
        <?php if (app()->auth::check()): ?>
            <div class="user-info">
                <div><?= htmlspecialchars(app()->auth::user()->login ?? 'login') ?></div>
                <div style="opacity: 0.6;">
                    <?= app()->auth::user()->isAdmin() ? 'Администратор' : 'Сотрудник' ?>
                </div>
            </div>

            <a href="<?= app()->route->getUrl('/logout') ?>" class="logout-link">
                <span>Выйти</span>
                <span>&rarr;</span>
            </a>
        <?php endif; ?>
    </div>
</aside>

<main>
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