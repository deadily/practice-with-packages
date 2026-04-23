<?php

namespace Controller;

use Model\User;
use Src\Request;
use Src\View;

class Api
{
    public function index(): void
    {
        $users = User::all(['id', 'full_name', 'login'])->toArray();
        (new View())->toJSON([
            'status' => 'success',
            'data' => $users
        ]);
    }

    public function login(Request $request): void
    {
        // 1. Правильно получаем данные
        $login = $request->get('login');
        $password = $request->get('password');

        // Опционально: проверка на пустоту
        if (empty($login) || empty($password)) {
             (new View())->toJSON([
                'status' => 'error',
                'message' => 'Login and password are required',
            ], 400);
            return;
        }

        // 2. Поиск пользователя
        // ВАЖНО: Убедитесь, что в БД пароль хранится именно как md5('staff')
        $user = User::where('login', $login)
                    ->where('password_hash', md5($password))
                    ->first();

        if (!$user) {
            (new View())->toJSON([
                'status' => 'error',
                'message' => 'Invalid credentials',
            ], 401);
            
            // 3. ОБЯЗАТЕЛЬНО прерываем выполнение, иначе код пойдет дальше
            return; 
        }

        $token = $user->generateApiToken();

        (new View())->toJSON([
            'status' => 'success',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->full_name
            ]
        ]);
    }

    public function getUser(Request $request): void
    {
        (new \Middlewares\AuthApiMiddleware())->handle($request);
        
        $user = $request->getAttribute('user');
        
        // Дополнительная проверка на всякий случай
        if (!$user) {
             (new View())->toJSON([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
            return;
        }

        (new View())->toJSON([
            'status' => 'success',
            'data' => $user->toArray()
        ]);
    }
}