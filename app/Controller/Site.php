<?php

namespace Controller;

use Model\Post;
use Model\User;
use Src\View;
use Src\Request;
use Src\Auth\Auth;


class Site
{
    public function hello(): string
    {
        return new View('site.hello', ['message' => 'hello working']);
    }

    public function index(Request $request): string
    {
        $posts = Post::where('id', $request->id)->get();
        return (new View())->render('site.post', ['posts' => $posts]);
    }

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
                app()->route->redirect('/staff');
            }
        }

        return new View('site.login', ['message' => 'Неправильные логин или пароль']);
    }

    public function logout(): void
    {
        Auth::logout();
        app()->route->redirect('/login');
    }

    public function admin_main(): string
    {
        $users = User::all();
        return new View('site.admin_main', ['users' => $users]);
    }

    public function admin_add_employee(): string
    {
        if (!app()->auth::check() || !app()->auth::user()->isAdmin()) {
            app()->route->redirect('/login');
        }
        return (new View())->render('site.admin_add_employee');
    }

    public function create_user(\Src\Request $request)
    {
        $login = $_POST['login'] ?? '';
        $full_name = $_POST['full_name'] ?? '';
        $role = $_POST['role'] ?? '2'; 
        $password = $_POST['password'] ?? '';

        if (empty($login) || empty($full_name) || empty($password)) {
            return app()->route->redirect('/admin_add_employee');
        }

        if (User::where('login', $login)->first()) {
            return app()->route->redirect('/admin_add_employee');
        }
        
        $roleId = (int)$role;
        if ($roleId !== 1 && $roleId !== 2) {
            $roleId = 2;
        }

        $user = new User();
        $user->login = $login;
        $user->full_name = $full_name;
        $user->role_id = $roleId;
        $user->password_hash = $password; 

        try {
            $user->save();
            return app()->route->redirect('/admin_main');
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return app()->route->redirect('/admin_add_employee');
        }
    }

    public function staff_buildings(): string
        {
            $user = app()->auth::user();
            return new View('site.staff_buildings', ['user' => $user]);
        }

    public function deleteUser(Request $request): void
        {
            if ($request->method === 'POST') {
                $id = $request->get('id');
                $user = User::find($id);
                if ($user) {
                    $user->delete();
                }
            }

            app()->route->redirect('/admin_main'); 
        }
}