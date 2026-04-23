<?php

namespace Controller;

use Model\Post;
use Model\User;
use Model\Building;
use Model\Room;
use Src\View;
use Src\Request;
use Src\Validator\Validator;

class Site
{
    // ... ваши остальные методы (hello, index, signup) ...

    /**
     * ⚠️ ТОЛЬКО ДЛЯ ТЕСТИРОВАНИЯ! УДАЛИТЬ ПЕРЕД ПРОДАКШЕНОМ!
     * Тестирует добавление комнаты и возвращает JSON
     */
    public function testAddRoom(): void
    {
        try {
            // 1. Создаем или находим здание
            $building = Building::firstOrCreate(
                ['name' => 'Тестовое здание'],
                ['address' => 'ул. Тестовая, 1']
            );

            // 2. Добавляем комнату со случайными данными
            $room = Room::create([
                'building_id' => $building->id,
                'name' => 'Комната #' . uniqid(),
                'area' => rand(20, 100),
                'seat_total' => rand(10, 50)
            ]);

            // 3. Пересчитываем статистику здания
            $building->recalculateStats();

            // 4. Возвращаем JSON ответ
            // Используем тот же класс View, что и в вашем API контроллере
            (new View())->toJSON([
                'status' => 'success',
                'message' => 'Комната успешно добавлена',
                'data' => [
                    'building' => [
                        'id' => $building->id,
                        'name' => $building->name,
                        'total_area' => $building->calculated_area,
                        'total_seats' => $building->calculated_seats
                    ],
                    'new_room' => [
                        'id' => $room->id,
                        'name' => $room->name,
                        'area' => $room->area,
                        'seats' => $room->seat_total
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            // Если произошла ошибка, возвращаем JSON с ошибкой
            (new View())->toJSON([
                'status' => 'error',
                'message' => 'Ошибка при выполнении теста: ' . $e->getMessage()
            ], 500);
        }
    }
}