<?php
$currentPath = $_SERVER['REQUEST_URI'] ?? '';
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление сотрудниками</title>
    <link rel="stylesheet" href="assets/style/admin_main.css">
</head>

<body>


<main>
    <div class="content-container">
        <div class="page-title-box">
            <h1>Управление сотрудниками</h1>
        </div>

        <div class="description-text">
            <p>Ваши возможности:</p>
            <p>
                Управление доступом: Создавайте учетные записи для новых сотрудников деканата. 
                Назначайте логины и пароли, обеспечивая безопасный доступ к системе только авторизованным пользователям.
            </p>
        </div>

        <div class="table-row header-row">
            <div class="cell fio">ФИО</div>
            <div class="cell">Логин</div>
            <div class="cell">Роль</div>
            <div class="cell action-box">
                <a class="create-btn" href="<?= app()->route->getUrl('/admin_add_employee') ?>">Создать сотрудника</a>
            </div>
        </div>

        <?php foreach ($users as $user): ?>
            <div class="table-row user-row">
                <div class="cell fio"><?= htmlspecialchars($user->full_name ?: '—') ?></div>
                <div class="cell"><?= htmlspecialchars($user->login) ?></div>
                <div class="cell">
                    <?= $user->isAdmin() ? 'admin' : 'staff' ?>
                </div>

                <div class="cell action-box">
                    <form action="<?= app()->route->getUrl('/delete-user') ?>" method="POST">
                        <input type="hidden" name="id" value="<?= $user->id ?>">
                        <button type="submit" class="delete-btn">Удалить</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>
</body>
</html>