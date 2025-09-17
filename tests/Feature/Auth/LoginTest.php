<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_login_with_email(): void
    {
        $this->seed();

        $password = 'password';
        $user = User::factory()->create([
            'password' => bcrypt($password),
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'login' => $user->email,
            'password' => $password,
        ]);

        $response->assertOk()
            ->assertJsonStructure([
                'user' => ['id', 'name', 'email'],
                'token',
            ]);
    }

    /** @test */
    public function user_can_login_with_username(): void
    {
        $this->seed();

        $password = 'password';
        $user = User::factory()->create([
            'password' => bcrypt($password),
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'login' => $user->username,
            'password' => $password,
        ]);

        $response->assertOk()
            ->assertJsonStructure([
                'user' => ['id', 'name', 'email'],
                'token',
            ]);
    }

    /** @test */
    public function login_fails_with_invalid_credentials(): void
    {
        $this->seed();

        $response = $this->postJson('/api/v1/auth/login', [
            'login' => 'invalid@example.com',
            'password' => 'wrong',
        ]);

        $response->assertStatus(401);
    }
}
