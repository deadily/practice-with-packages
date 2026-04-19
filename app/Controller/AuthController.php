<?php

namespace Controller;

use Src\View;
use Src\Request;
use Src\Auth\Auth;

class AuthController
{
    public function login(Request $request): string
    {
        if ($request->method === 'GET') {
            return new View('site.login');
        }

        if (Auth::attempt($request->all())) {
            unset($_SESSION['message']);

            if (Auth::user()->isAdmin()) {
                app()->route->redirect('/admin_main');
            } else {
                app()->route->redirect('/staff_buildings');
            }
        }

        return new View('site.login', ['message' => 'Неправильные логин или пароль']);
    }

    public function logout(): void
    {
        Auth::logout();
        app()->route->redirect('/login');
    }
}