<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{

    #Все роли
    public function index(Request $req)
    {
        $validated = $req->validate([
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $perPage = $validated['per_page'] ?? 10;
        return Role::paginate($perPage);
    }

    #Одна роль + все пользователи которые принадлежат этой роли
    public function show(Request $req, Role $role)
    {
        $validated = $req->validate([
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $perPage = $validated['per_page'] ?? 10;

        $users = $role->users()->paginate($perPage);

        return response()->json([
            'role' => $role,
            'users' => $users,
        ]);
    }

}
