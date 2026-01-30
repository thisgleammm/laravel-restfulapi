<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testRegisterSuccess(): void
    {
        $this->post('/api/users', [
            'username' => "gleam",
            'password' => "rahasia",
            'name' => "thisgleam"
        ])->assertStatus(201)
            ->assertJson([
                "data" => [
                    'username' => "gleam",
                    'name' => "thisgleam"
                ]
            ]);
    }
    public function testRegisterFailed(): void
    {
        $this->post('/api/users', [
            'username' => "",
            'password' => "",
            'name' => ""
        ])->assertStatus(400)
            ->assertJson([
                "errors" => [
                    'username' => [
                        "The username field is required."
                    ],
                    'password' => [
                        "The password field is required."
                    ],
                    'name' => [
                        "The name field is required."
                    ]
                ]
            ]);
    }
    public function testRegisterUsernameAlreadyExists(): void
    {
        $this->testRegisterSuccess();
        $this->post('/api/users', [
            'username' => "gleam",
            'password' => "rahasia",
            'name' => "thisgleam"
        ])->assertStatus(400)
            ->assertJson([
                "errors" => [
                    'username' => [
                        "username already registered"
                    ],
                ]
            ]);
    }

    public function testLoginSuccess()
    {
        $this->seed([UserSeeder::class]);
        $this->post('/api/users/login', [
            'username' => "test",
            'password' => 'test',
        ])->assertStatus(200)
            ->assertJson([
                "data" => [
                    'username' => "test",
                    'name' => "test"
                ]
            ]);

        $user = User::where('username', 'test')->first();
        self::assertNotNull($user->token);
    }

    public function testLoginFailedUsernameNotFound()
    {
        $this->post('/api/users/login', [
            'username' => "test",
            'password' => 'test',
        ])->assertStatus(401)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "username or password wrong"
                    ]
                ]
            ]);
    }
    public function testLoginFailedPasswordWrong()
    {
        $this->seed([UserSeeder::class]);
        $this->post('/api/users/login', [
            'username' => "test",
            'password' => "salah",
        ])->assertStatus(401)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "username or password wrong"
                    ]
                ]
            ]);
    }

}
