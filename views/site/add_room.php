<?php
$currentPath = $_SERVER['REQUEST_URI'] ?? '/rooms?action=create';
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Создание помещения</title>
    <link rel="stylesheet" href="assets/style/add_room.css">
</head>

<body>

<aside>
    <div>
        <div class="logo">
            Учебно-<br>методическое<br>управление
        </div>
        <nav>
            <?php if (app()->auth::check() && !app()->auth::user()->isAdmin()): ?>
                <a href="<?= app()->route->getUrl('/staff_buildings') ?>" class="<?= ($currentPath === '/staff_buildings') ? 'active' : '' ?>">
                    Здания
                </a>
                <a href="<?= app()->route->getUrl('/staff_rooms') ?>" class="<?= ($currentPath === '/staff_rooms') ? 'active' : '' ?>">
                    Помещения
                </a>
            <?php endif; ?>
        </nav>
    </div>

    <div class="sidebar-footer">
        <?php if (app()->auth::check()): ?>
            <div class="user-info">
                <div><?= htmlspecialchars(app()->auth::user()->login ?? 'loginexample') ?></div>
                <div style="opacity: 0.6;">Сотрудник</div>
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
            <h1>Создание помещения</h1>
        </div>

        <div class="form-container">
            <form action="<?= app()->route->getUrl('/store_room') ?>" method="POST">
                <div class="form-grid">
                    <label class="form-label">Здание</label>
                    <div class="form-input-wrapper select-wrapper">
                        <select name="building_id" required>
                            <option value="">Выберите здание</option>
                            <?php foreach ($buildings as $building): ?>
                                <option value="<?= $building->id ?>" <?= ($selectedBuildingId == $building->id) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($building->name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="empty-cell"></div>

                    <label class="form-label">Номер</label>
                    <div class="form-input-wrapper">
                        <input type="text" name="room_number" placeholder="Например: 010" required>
                    </div>
                    <div class="empty-cell"></div>


                    <label class="form-label">Тип</label>
                    <div class="form-input-wrapper select-wrapper">
                        <select name="room_type_id" required>
                            <option value="">Выберите тип</option>
                            <?php foreach ($roomTypes as $type): ?>
                                <option value="<?= $type->id ?>">
                                    <?= htmlspecialchars($type->type_name) ?>
                                    <?= $type->is_educational ? ' (учебное)' : '' ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="empty-cell"></div>

                    <label class="form-label">Площадь (м2)</label>
                    <div class="form-input-wrapper">
                        <input type="number" step="0.01" name="area" placeholder="63" required>
                    </div>
                    <div class="empty-cell"></div>

                    <label class="form-label">Кол-во мест</label>
                    <div class="form-input-wrapper">
                        <input type="number" name="seat_total" placeholder="67" required>
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