<?php

namespace Middlewares;

use Model\User;
use Src\Request;
use Src\View;

class AuthApiMiddleware
{
    public function handle(Request $request): Request
    {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';

        if (empty($authHeader) || strpos($authHeader, 'Bearer ') !== 0) {
            (new View())->toJSON([
                'status' => 'error',
                'message' => 'Authorization header missing or invalid format'
            ], 401);
        }

        $token = substr($authHeader, 7);
        $user = User::where('api_token', $token)->first();

        if (!$user) {
            (new View())->toJSON([
                'status' => 'error',
                'message' => 'Invalid token'
            ], 401);
        }

        $request->setAttribute('user', $user);
        
        return $request;
    }
}