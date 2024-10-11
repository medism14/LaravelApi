<?php

namespace Tests\Unit;

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    // Test de si un utilisateur arrive à s'enregistrer
    public function test_register(): void
    {
        // Passer une requête pour enregistrer l'utilisateur
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // Vérifier si la réponse correspond à nos exigences
        $response->assertStatus(200)
            ->assertJsonStructure(['user', 'access_token', 'token_type']);

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
        ]);
    }

    // Test de la connexion d'un utilisateur
    public function test_login(): void
    {
        // Création d'un utilisateur normal
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        // Faire une requête pour se connecter
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        // Vérifier si la réponse correspond à nos exigences
        $response->assertStatus(200)
            ->assertJsonStructure(['user', 'access_token', 'token_type']);
    }

    // Test de la déconnexion d'un utilisateur
    public function test_logout(): void
    {
        // Création d'un utilisateur normal
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        // Envoi d'une requête pour se déconnecter
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/logout');

        // Vérifier si la réponse correspond à nos exigences
        $response->assertStatus(200)
            ->assertJsonStructure(['success']);
    }

    // Test de la réception des informations
    public function test_get_all_users(): void
    {
        // Création d'un administrateur
        $user = User::factory()->withAdminRole()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        // Envoi d'une requête pour récupérer les utilisateurs
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/users');

        // Vérifier si la réponse correspond à nos exigences
        $response->assertStatus(200)
            ->assertJsonStructure(['users']);
    }

    // Test de la récupération des informations d'un utilisateur
    public function test_get_user(): void
    {
        // Création d'un utilisateur normal
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        // Envoi d'une requête pour récupérer un utilisateur
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/users/' . $user->id);

        // Vérifier si la réponse correspond à nos exigences
        $response->assertStatus(200)
            ->assertJsonStructure(['user']);
    }

    // Test de la modification d'un utilisateur
    public function test_update_user(): void
    {
        // Création d'un utilisateur normal
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        // Envoi d'une requête pour modifier un utilisateur
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson('/api/users/' . $user->id, [
            'name' => 'Med Ismael',
            'email' => 'medismael14@gmail.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // Vérifier si la réponse correspond à nos exigences
        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'user']);

        $this->assertDatabaseHas('users', [
            'email' => 'medismael14@gmail.com',
        ]);
    }

    // Test du passage d'utilisateur en administrateur
    public function test_make_admin(): void
    {
        // Création d'un utilisateur normal
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        // Envoi d'une requête pour permettre à un utilisateur de passer administrateur
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson('/api/makeAdmin/4f3a1b2c5d6e7f8a9b0c1d2e3f4a5b6c/' . $user->id);

        // Vérifier si la réponse correspond à nos exigences
        $response->assertStatus(200)
            ->assertJsonStructure(['success']);
    }

    // Test de la suppression d'un utilisateur
    public function test_delete_user(): void
    {
        // Création d'un utilisateur normal
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        // Envoi d'une requête pour supprimer un utilisateur
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson('/api/users/' . $user->id);

        // Vérifier si la réponse correspond à nos exigences
        $response->assertStatus(200)
            ->assertJsonStructure(['success']);
    }

    // Test de récupération des réservations d'un utilisateur
    public function test_get_my_reservations(): void
    {
        // Création d'un utilisateur normal
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        // Envoi d'une requête pour récupérer les reservations de utilisateur initiant la requête
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/myReservations');

        // Vérifier si la réponse correspond à nos exigences
        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'reservations']);
    }

    // Test de l'annulation d'une réservation pour un utilisateur
    public function test_cancel_user_reservation(): void
    {
        // Création d'une réservation et récupération de l'utilisateur
        $reservation = Reservation::factory()->create();
        $user = $reservation->user;

        // Créer un token pour l'utilisateur
        $token = $user->createToken('auth_token')->plainTextToken;

        // Envoi d'une requête pour annuler la réservation
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson('/api/cancelReservation/' . $reservation->id);

        // Vérification de la réponse
        $response->assertStatus(200)
            ->assertJsonStructure(['success']);
    }

}
