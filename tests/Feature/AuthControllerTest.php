<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_creates_new_user_and_returns_token(): void
    {
        $data = [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/auth/register', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'user' => ['id', 'name', 'email'],
                'token',
            ])
            ->assertJson([
                'user' => [
                    'name' => 'Test User',
                    'email' => 'testuser@example.com',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
        ]);
    }

    public function test_register_fails_with_missing_name(): void
    {
        $data = [
            'email' => 'testuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/auth/register', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_register_fails_with_missing_email(): void
    {
        $data = [
            'name' => 'Test User',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/auth/register', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_register_fails_with_invalid_email_format(): void
    {
        $data = [
            'name' => 'Test User',
            'email' => 'invalid-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/auth/register', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_register_fails_with_duplicate_email(): void
    {
        User::factory()->create(['email' => 'testuser@example.com']);

        $data = [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/auth/register', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_register_fails_with_short_password(): void
    {
        $data = [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'short',
            'password_confirmation' => 'short',
        ];

        $response = $this->postJson('/api/auth/register', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    public function test_register_fails_with_unconfirmed_password(): void
    {
        $data = [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different_password',
        ];

        $response = $this->postJson('/api/auth/register', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    public function test_register_fails_with_missing_password_confirmation(): void
    {
        $data = [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/auth/register', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    public function test_login_returns_token_for_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'testuser@example.com',
            'password' => Hash::make('password123'),
        ]);

        $data = [
            'email' => 'testuser@example.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/auth/login', $data);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'user' => ['id', 'name', 'email'],
                'token',
            ])
            ->assertJson([
                'user' => [
                    'email' => 'testuser@example.com',
                ],
            ]);
    }

    public function test_login_fails_with_invalid_email(): void
    {
        User::factory()->create([
            'email' => 'testuser@example.com',
            'password' => Hash::make('password123'),
        ]);

        $data = [
            'email' => 'wrong@example.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/auth/login', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_login_fails_with_invalid_password(): void
    {
        User::factory()->create([
            'email' => 'testuser@example.com',
            'password' => Hash::make('password123'),
        ]);

        $data = [
            'email' => 'testuser@example.com',
            'password' => 'wrongpassword',
        ];

        $response = $this->postJson('/api/auth/login', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_login_fails_with_missing_email(): void
    {
        $data = [
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/auth/login', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_login_fails_with_missing_password(): void
    {
        $data = [
            'email' => 'testuser@example.com',
        ];

        $response = $this->postJson('/api/auth/login', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    public function test_login_fails_with_invalid_email_format(): void
    {
        $data = [
            'email' => 'invalid-email',
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/auth/login', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_logout_deletes_token_and_returns_success(): void
    {
        $user = User::factory()->create();

        $token = $user->createToken('auth-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/auth/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Logged out successfully']);
    }

    public function test_logout_returns_unauthorized_for_guest_user(): void
    {
        $response = $this->postJson('/api/auth/logout');

        $response->assertStatus(401);
    }

    public function test_me_returns_authenticated_user_data(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->getJson('/api/auth/me');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['id', 'name', 'email'],
            ])
            ->assertJson([
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
            ]);
    }

    public function test_me_returns_unauthorized_for_guest_user(): void
    {
        $response = $this->getJson('/api/auth/me');

        $response->assertStatus(401);
    }
}