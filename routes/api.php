<?php

use Src\Route;

Route::add('GET', '/', [Controller\Api::class, 'index']);
Route::add('POST', '/login', [Controller\Api::class, 'login']);
Route::add('GET', '/user', [Controller\Api::class, 'getUser']);
$router->get('/test-add-room', [\Controller\Api::class, 'testAddRoom']);