<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
}
