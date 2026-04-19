<?php

use Src\Route;


Route::add('GET', '/hello', [Controller\Site::class, 'hello'])
    ->middleware('auth');

//Авторизация
Route::add(['GET', 'POST'], '/login', [Controller\AuthController::class, 'login']);
Route::add('GET', '/logout', [Controller\AuthController::class, 'logout']);

//Админ

Route::add(['GET', 'POST'], '/admin_main', [Controller\AdminController::class, 'admin_main'])
    ->middleware('admin');

Route::add(['GET', 'POST'], '/admin_add_employee', [Controller\AdminController::class, 'admin_add_employee'])
    ->middleware('admin');

Route::add(['POST'], '/create_user', [Controller\AdminController::class, 'create_user'])
    ->middleware('admin');

Route::add('POST', '/delete-user', [Controller\AdminController::class, 'deleteUser'])
    ->middleware('admin');


//Сотрудник
Route::add('GET', '/staff_buildings', [Controller\StaffController::class, 'staff_buildings'])
    ->middleware('auth');

Route::add('POST', '/staff_buildings', [Controller\StaffController::class, 'staff_delete_building'])
    ->middleware('auth');

Route::add('GET', '/add_building', [Controller\StaffController::class, 'create_building'])
    ->middleware('auth');

Route::add('POST', '/store_building', [Controller\StaffController::class, 'store_building'])
    ->middleware('auth');

Route::add('GET', '/staff_rooms', [Controller\StaffController::class, 'staff_rooms'])
    ->middleware('auth');

Route::add('POST', '/staff_rooms', [Controller\StaffController::class, 'staff_delete_room'])
    ->middleware('auth');

Route::add('GET', '/add_room', [Controller\StaffController::class, 'create_room'])
    ->middleware('auth');

Route::add('POST', '/store_room', [Controller\StaffController::class, 'store_room'])
    ->middleware('auth');