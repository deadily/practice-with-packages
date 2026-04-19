<?php
$currentPath = $_SERVER['REQUEST_URI'] ?? '/staff_buildings';

?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Просмотр зданий</title>
    <link rel="stylesheet" href="assets/style/staff_buildings.css">
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
            <h1>Управление зданиями</h1>
        </div>

        <div class="description-text" style="margin-bottom: 30px;">
            <p><strong>Ваши возможности:</strong></p>
            <ul>
                <li>
                    <strong>Управление объектами:</strong> Добавляйте новые здания и помещения в базу данных. Указывайте точные характеристики: номер аудитории, её вид (лекционная, лаборатория, кабинет), площадь и количество посадочных мест.
                </li>
                <li>
                    <strong>Навигация по фонду:</strong> Быстро находите нужные аудитории, фильтруя их по конкретным зданиям. Просматривайте полную информацию о каждом помещении.
                </li>
                <li>
                    <strong>Аналитика и отчетность:</strong>
                    <ul class="sub-list">
                        <li>Получайте автоматический расчет общей площади учебных аудиторий как по отдельным зданиям, так и по всему учебному заведению.</li>
                        <li>Контролируйте вместимость фонда: просматривайте общее количество посадочных мест в разрезе зданий.</li>
                    </ul>
                </li>
            </ul>
        </div>

        <div class="summary-grid">
            <?php
            $totalAreaAll = 0;
            $totalSeatsAll = 0;

            foreach ($buildings as $b) {
                $totalAreaAll += (float)$b->calculated_area;
                $totalSeatsAll += (int)$b->calculated_seats;
            }
            ?>

            <div class="summary-box summary-area">
                Общая площадь: <?= (int)$totalAreaAll ?> м2
            </div>
            <div class="summary-box summary-seats">
                Общее кол-во посадочных мест: <?= (int)$totalSeatsAll ?>
            </div>
            <div class="summary-box summary-create">
                <a class="create-btn" href="<?= app()->route->getUrl('/add_building') ?>?action=create">
                    Создать здание
                </a>
            </div>
        </div>

        <div class="header-row">
            <div class="cell">Название здания</div>
            <div class="cell">Адрес здания</div>
            <div class="cell">Площадь здания</div>
            <div class="cell" style="justify-content:flex-end; padding-right:20px;"></div>
        </div>

        <?php if (!empty($buildings)): ?>
            <?php foreach ($buildings as $building): ?>
                <div class="data-row">
                    <div class="cell name-cell">
                        <?= htmlspecialchars($building->name ?? '—') ?>
                    </div>
                    <div class="cell addr-cell">
                        <?= htmlspecialchars($building->address ?? '—') ?>
                    </div>
                    <div class="cell area-cell">
                        <?= htmlspecialchars($building->area ?? '—') ?> м2
                    </div>
                    <div class="cell actions-cell">
                        <div class="actions-inner">
                            <a class="action-btn"
                               href="<?= app()->route->getUrl('staff_rooms') ?>?building_id=<?= (int)($building->id ?? 0) ?>">
                                Посмотреть помещения
                            </a>
                            <form action="<?= app()->route->getUrl('/staff_buildings') ?>" method="POST">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= (int)($building->id ?? 0) ?>">
                                <button type="submit" class="delete-btn">Удалить</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="data-row">
                <div class="cell" colspan="4" style="text-align:center;">Зданий не найдено</div>
            </div>
        <?php endif; ?>

    </div>
</main>

</body>
</html>