<?php
$currentPath = $_SERVER['REQUEST_URI'] ?? '/buildings?action=create';
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Создание помещения</title>
    <link rel="stylesheet" href="assets/style/add_building.css">
</head>

<body>

<aside>
    <div>
        <div class="logo">
            Учебно-<br>методическое<br>управление
        </div>
        <nav>
            <?php if (app()->auth::check()): ?>
                <?php if (app()->auth::user()->isAdmin()): ?>
                    <a href="<?= app()->route->getUrl('/admin_main') ?>" class="<?= ($currentPath === '/admin_main') ? 'active' : '' ?>">
                        Управление пользователями
                    </a>
                <?php else: ?>
                    <a href="<?= app()->route->getUrl('/staff_buildings') ?>" class="<?= ($currentPath === '/staff_buildings') ? 'active' : '' ?>">
                        Здания
                    </a>
                    <a href="<?= app()->route->getUrl('/staff_rooms') ?>" class="<?= ($currentPath === '/staff_rooms') ? 'active' : '' ?>">
                        Помещения
                    </a>
                <?php endif; ?>
            <?php endif; ?>
        </nav>
    </div>

    <div class="sidebar-footer">
        <?php if (app()->auth::check()): ?>
            <div class="user-info">
                <div><?= htmlspecialchars(app()->auth::user()->login ?? 'Гость') ?></div>
                <div style="opacity: 0.6;">
                    <?php if (app()->auth::user()->isAdmin()): ?>
                        Администратор
                    <?php else: ?>
                        Сотрудник
                    <?php endif; ?>
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
            <h1>Создание здания</h1>
        </div>

        <div class="form-container">
            <form action="<?= app()->route->getUrl('/store_building') ?>" method="POST">
                <div class="form-grid">
                    <label class="form-label">Название</label>
                    <div class="form-input-wrapper">
                        <input type="text" name="name" placeholder="Например: Корпус А" required>
                    </div>
                    <div class="empty-cell"></div>

                    <label class="form-label">Адрес</label>
                    <div class="form-input-wrapper">
                        <input type="text" name="address" placeholder="Район X, улица Y, дом 5" required>
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