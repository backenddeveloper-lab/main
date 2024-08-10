<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    #Все пользователи + роли пользователей
    public function index(Request $req)
    {
        $validated = $req->validate([
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $perPage = $validated['per_page'] ?? 10;
        return User::with('roles')->paginate($perPage);
    }

    #Один пользователь + роли пользователя
    public function show(User $user)
    {
        return $user->load('roles');
    }
}
