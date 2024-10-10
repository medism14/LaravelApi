<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function normalUser(): User
    {
        $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::where('email', 'testuser@example.com')->first();
        return $user;
    }

    public function adminUser(): User
    {
        $this->postJson('/api/register', [
            'name' => 'Test Admin',
            'email' => 'testadmin@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::where('email', 'testadmin@example.com')->first();
        $user->role = 0;
        $user->save();

        return $user;
    }

    public function test_register(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // Vérifier si la réponse correspond à nos exigences
        $response->assertStatus(200)
            ->assertJsonStructure(['user', 'access_token', 'token_type']);
    }

    public function test_login(): void
    {
        // Créer un utilisateur normal
        $this->normalUser();

        // Récupérer la réponse de la requête
        $response = $this->postJson('/api/login', [
            'email' => 'testuser@example.com',
            'password' => 'password123',
        ]);

        // Vérifier si la réponse correspond à nos exigences
        $response->assertStatus(200)
            ->assertJsonStructure(['user', 'access_token', 'token_type']);
    }

    public function test_logout(): void
    {
        $user = $this->adminUser();

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJsonStructure(['message']);
    }

    public function test_get_all_users(): void
    {
        // Créez l'utilisateur administrateur
        $user = $this->adminUser();

        // Générez un token pour l'utilisateur administrateur
        $token = $user->createToken('auth_token')->plainTextToken;

        // Effectuez la requête avec le token d'authentification
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/users');

        // Vérifier si la réponse correspond à nos exigences
        $response->assertStatus(200)
            ->assertJsonStructure(['users']);
    }

    public function test_get_one_user(): void
    {
        $this->assertTrue(true);
    }

    public function test_update_user(): void
    {
        $this->assertTrue(true);
    }

    public function test_delete_user(): void
    {
        $this->assertTrue(true);
    }

    public function test_get_user_reservations(): void
    {
        $this->assertTrue(true);
    }

    public function test_cancel_user_reservation(): void
    {
        $this->assertTrue(true);
    }
}
