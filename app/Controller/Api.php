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
        
        $login = $request->get('','login');
        $password = $request->get('','password');

        echo $login.PHP_EOL. $password;

        $user = User::where('login', $login)
                    ->where('password_hash', md5($password))
                    ->first();

        if (!$user) {
            (new View())->toJSON([
                'status' => 'error',
                'message' => 'Invalid credentials',
            ], 401);
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
        
        (new View())->toJSON([
            'status' => 'success',
            'data' => $user->toArray()
        ]);
    }
}