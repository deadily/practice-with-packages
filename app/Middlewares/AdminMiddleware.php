<?php

namespace Middlewares;

use Src\Auth\Auth;
use Src\Middleware;

class AdminMiddleware extends Middleware
{
    public function handle(): bool
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            app()->route->redirect('/hello');
            return false;
        }
        return true;
    }
}