<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_register_with_valid_data()
    {
        $response = $this->postJson('/api/v1/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'SecurePass123',
            'password_confirmation' => 'SecurePass123',
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => ['user', 'token']
                 ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
        ]);
    }

    /** @test */
    public function user_cannot_register_with_invalid_email()
    {
        $response = $this->postJson('/api/v1/register', [
            'name' => 'John Doe',
            'email' => 'invalid-email',
            'password' => 'SecurePass123',
            'password_confirmation' => 'SecurePass123',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function user_can_login_with_correct_credentials()
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => bcrypt('SecurePass123'),
        ]);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'john@example.com',
            'password' => 'SecurePass123',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => ['user', 'token']
                 ]);
    }

    /** @test */
    public function user_cannot_login_with_incorrect_password()
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => bcrypt('SecurePass123'),
        ]);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'john@example.com',
            'password' => 'WrongPassword',
        ]);

        $response->assertStatus(401);
    }

    /** @test */
    public function authenticated_user_can_logout()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/v1/logout');

        $response->assertStatus(200);
    }

    /** @test */
    public function authenticated_user_can_get_profile()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/v1/me');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'data' => [
                         'id' => $user->id,
                         'email' => $user->email,
                     ]
                 ]);
    }
}
