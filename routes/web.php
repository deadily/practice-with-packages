<?php

use Src\Route;

Route::add('GET', '/hello', [Controller\Site::class, 'hello'])
    ->middleware('auth');

Route::add(['GET', 'POST'], '/login', [Controller\Site::class, 'login']);
Route::add('GET', '/logout', [Controller\Site::class, 'logout']);

Route::add(['GET', 'POST'], '/signup', [Controller\Site::class, 'signup'])
    ->middleware('admin');

Route::add(['GET', 'POST'], '/buildings', [Controller\Site::class, 'buildings'])
    ->middleware('auth');

Route::add(['GET', 'POST'], '/rooms', [Controller\Site::class, 'rooms'])
    ->middleware('auth');

Route::add('GET', '/stats', [Controller\Site::class, 'stats'])
    ->middleware('auth');