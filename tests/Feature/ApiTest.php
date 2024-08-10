<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;

class ApiTest extends TestCase
{
    use DatabaseTransactions;

    public function test_get_all_users_with_roles()
    {
        $response = $this->getJson('/api/users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'current_page',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                        'email_verified_at',
                        'created_at',
                        'updated_at',
                        'roles' => [
                            '*' => [
                                'id',
                                'name',
                                'created_at',
                                'updated_at',
                                'pivot' => [
                                    'user_id',
                                    'role_id'
                                ]
                            ]
                        ]
                    ]
                ],
                'first_page_url',
                'from',
                'last_page',
                'last_page_url',
                'links',
                'next_page_url',
                'path',
                'per_page',
                'prev_page_url',
                'to',
                'total'
            ]);
    }

    public function test_get_single_user_with_roles()
    {
        $user = User::with('roles')->first();

        $response = $this->getJson('/api/users/' . $user->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'email',
                'email_verified_at',
                'created_at',
                'updated_at',
                'roles' => [
                    '*' => [
                        'id',
                        'name',
                        'created_at',
                        'updated_at',
                        'pivot' => [
                            'user_id',
                            'role_id'
                        ]
                    ]
                ]
            ]);
    }

    public function test_get_all_roles()
    {
        $response = $this->getJson('/api/roles');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'current_page',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'created_at',
                        'updated_at'
                    ]
                ],
                'first_page_url',
                'from',
                'last_page',
                'last_page_url',
                'links',
                'next_page_url',
                'path',
                'per_page',
                'prev_page_url',
                'to',
                'total'
            ]);
    }

    public function test_get_single_role_with_users()
    {
        $role = Role::with('users')->first();

        $response = $this->getJson('/api/roles/' . $role->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'role' => [
                    'id',
                    'name',
                    'created_at',
                    'updated_at'
                ],
                'users' => [
                    'current_page',
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'email',
                            'email_verified_at',
                            'created_at',
                            'updated_at',
                            'pivot' => [
                                'role_id',
                                'user_id'
                            ]
                        ]
                    ],
                    'first_page_url',
                    'from',
                    'last_page',
                    'last_page_url',
                    'links',
                    'next_page_url',
                    'path',
                    'per_page',
                    'prev_page_url',
                    'to',
                    'total'
                ]
            ]);
    }

    public function test_per_page_validation()
    {
        $response = $this->getJson('/api/users?per_page=0');
        $response->assertStatus(422)
            ->assertJsonValidationErrors('per_page');

        $response = $this->getJson('/api/users?per_page=101');
        $response->assertStatus(422)
            ->assertJsonValidationErrors('per_page');

        $response = $this->getJson('/api/roles?per_page=0');
        $response->assertStatus(422)
            ->assertJsonValidationErrors('per_page');

        $response = $this->getJson('/api/roles?per_page=101');
        $response->assertStatus(422)
            ->assertJsonValidationErrors('per_page');
    }

    public function test_user_not_found()
    {
        $response = $this->getJson('/api/users/9999'); // ID, который не существует

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Resource not found.'
            ]);
    }

    public function test_role_not_found()
    {
        $response = $this->getJson('/api/roles/9999'); // ID, который не существует

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Resource not found.'
            ]);
    }
}
