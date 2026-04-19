<?php
$currentPath = $_SERVER['REQUEST_URI'] ?? '';
$isAdmin = app()->auth::check() && app()->auth::user()->isAdmin();
$isStaff = app()->auth::check() && !app()->auth::user()->isAdmin();
?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= app()->route->getUrl('/assets/style/main.css') ?>">
    <title><?= $pageTitle ?? 'Учебно-методическое управление' ?></title>
</head>
<body>

<?php if (app()->auth::check()): ?>
<aside class="sidebar-wrapper">
    <div class="logo-block">
        <div class="logo">
            Учебно-
методическое
управление
        </div>
    </div>

    <nav class="sidebar-nav">
        <?php if (app()->auth::check()): ?>
            
            <?php if ($isAdmin): ?>
                <a href="<?= app()->route->getUrl('/admin_main') ?>" class="<?= ($currentPath == '/admin_main') ? 'active' : '' ?>">
                    Управление пользователями
                </a>
            <?php endif; ?>

            <?php if ($isStaff): ?>
                <a href="<?= app()->route->getUrl('/staff_buildings') ?>" class="<?= ($currentPath == '/staff_buildings') ? 'active' : '' ?>">
                    Здания
                </a>
                <a href="<?= app()->route->getUrl('/staff_rooms') ?>" class="<?= ($currentPath == '/staff_rooms') ? 'active' : '' ?>">
                    Помещения
                </a>
            <?php endif; ?>

            <div class="logout-block">
                <div class="user-info">
                    <div><?= htmlspecialchars(app()->auth::user()->login ?? 'Гость') ?></div>
                    <div style="opacity: 0.6;">
                        <?= $isAdmin ? 'Администратор' : 'Сотрудник' ?>
                    </div>
                </div>
                <a href="<?= app()->route->getUrl('/logout') ?>" class="logout-link">
                    <span>Выйти</span>
                    <span>&rarr;</span>
                </a>
            </div>
        <?php endif; ?>
    </nav>
</aside>
 <?php endif; ?>

<main class="content-wrapper">
    <?= $content ?? '' ?>
</main>

</body>
</html>