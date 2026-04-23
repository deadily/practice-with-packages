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
        $login = $request->get('login');
        $password = $request->get('password');

        if (empty($login) || empty($password)) {
             (new View())->toJSON([
                'status' => 'error',
                'message' => 'Login and password are required',
            ], 400);
            return;
        }

        $user = User::where('login', $login)
                    ->where('password_hash', md5($password))
                    ->first();

        if (!$user) {
            (new View())->toJSON([
                'status' => 'error',
                'message' => 'Invalid credentials',
            ], 401);
            
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