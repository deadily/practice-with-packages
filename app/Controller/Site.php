<?php

namespace Controller;

use Model\Post;
use Model\User;
use Src\View;
use Src\Request;
use Src\Validator\Validator;


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

    public function signup(Request $request): string
    {
    if ($request->method === 'POST') {

        $validator = new Validator($request->all(), [
            'name' => ['required'],
            'login' => ['required', 'unique:users,login'],
            'password' => ['required']
        ], [
            'required' => 'Поле :field пусто',
            'unique' => 'Поле :field должно быть уникально'
        ]);

        if($validator->fails()){
            return new View('site.signup',
                ['message' => json_encode($validator->errors(), JSON_UNESCAPED_UNICODE)]);
        }

        if (User::create($request->all())) {
            app()->route->redirect('/login');
        }
    }
    return new View('site.signup');
    }
}