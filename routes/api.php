<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RoleController;

#Все пользователи + роли пользователей
Route::get('users', [UserController::class, 'index']);

#Один пользователь + роли пользователя
Route::get('users/{user}', [UserController::class, 'show']);

#Все роли
Route::get('roles', [RoleController::class, 'index']);

#Одна роль + все пользователи которые принадлежат этой роли
Route::get('roles/{role}', [RoleController::class, 'show']);
