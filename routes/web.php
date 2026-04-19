<?php

use Src\Route;

Route::add('GET', '/hello', [Controller\Site::class, 'hello'])
    ->middleware('auth');

Route::add(['GET', 'POST'], '/login', [Controller\Site::class, 'login']);
Route::add('GET', '/logout', [Controller\Site::class, 'logout']);

Route::add(['GET', 'POST'], '/admin_main', [Controller\Site::class, 'admin_main'])
    ->middleware('admin');

Route::add(['GET', 'POST'], '/admin_add_employee', [Controller\Site::class, 'admin_add_employee'])
    ->middleware('admin');

Route::add(['POST'], '/create_user', [Controller\Site::class, 'create_user'])
    ->middleware('admin');

Route::add(['GET', 'POST'], '/staff_buildings', [Controller\Site::class, 'staff_buildings'])
    ->middleware('auth');

Route::add(['GET', 'POST'], '/staff_rooms', [Controller\Site::class, 'staff_rooms'])
    ->middleware('auth');

Route::add('GET', '/stats', [Controller\Site::class, 'stats'])
    ->middleware('auth');

Route::add('POST', '/delete-user', [Controller\Site::class, 'deleteUser'])
    ->middleware('admin');