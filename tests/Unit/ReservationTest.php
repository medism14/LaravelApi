<?php

namespace Tests\Unit;

use App\Models\Event;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationTest extends TestCase
{

    use RefreshDatabase;

    // Test de la récupération de tout
    public function test_get_all_reservation(): void
    {
        // Création de l'utilisateur et du token
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        // Envoi d'une requête pour récupérer toutes les réservations
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/reservations');

        // Vérifier si la réponse correspond à nos exigences
        $response->assertStatus(200)
            ->assertJsonStructure(['reservations']);
    }

    // Test pour la création 
    public function test_create_reservation(): void
    {
        // Création de l'utilisateur et du token
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        // Création d'un événement
        $event = Event::factory()->create();

        // Envoi d'une requête pour créer une réservation
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/reservations', [
            'number_of_seat' => '3',
            'user_id' => $user->id,
            'event_id' => $event->id,
        ]);

        // Vérifier si la réponse correspond à nos exigences
        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'reservation']);
    }

    // Test pour la récupération
    public function test_get_reservation(): void
    {
        // Création de l'utilisateur et du token
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        $reservation = Reservation::factory()->create();

        // Envoi d'une requête pour récuperer une réservation
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/reservations/' . $reservation->id);

        // Vérifier si la réponse correspond à nos exigences
        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'reservation']);
    }

    // Test pour la modification
    public function test_update_reservation(): void
    {
        // Création de l'utilisateur et du token
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        $reservation = Reservation::factory()->create();
        $event = Event::factory()->create();

        // Envoi d'une requête pour modifier une réservation
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson('/api/reservations/' . $reservation->id, [
            'status' => 'cancelled',
            'number_of_seat' => '2',
            'user_id' => $user->id,
            'event_id' => $event->id,
        ]);

        // Vérifier si la réponse correspond à nos exigences
        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'reservation']);

        $this->assertDatabaseHas('reservations', [
            'user_id' => $user->id,
        ]);
    }

    // Test pour la suppression
    public function test_delete_reservation(): void
    {
        // Création de l'utilisateur et du token
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        $reservation = Reservation::factory()->create();

        // Envoi d'une requête pour supprimer une réservation
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson('/api/reservations/' . $reservation->id);

        // Vérifier si la réponse correspond à nos exigences
        $response->assertStatus(200)
            ->assertJsonStructure(['success']);
    }
}
