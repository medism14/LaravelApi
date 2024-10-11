<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventTest extends TestCase
{

    use RefreshDatabase;

    // Test de la récupération de tout
    public function test_get_all_events(): void
    {   
        // Création d'un utilisateur et récupéraiton du token
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        // Envoi d'une requête pour récupérer tout les événements
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/events?search=Dégats de l\'ia&start_date=11-10-2024');

        // Vérifier si la réponse correspond à nos exigences
        $response->assertStatus(200)->assertJsonStructure(['events']);
    }

    // Test pour la création 
    public function test_create_event(): void
    {
        // Création d'un utilisateur et récupéraiton du token
        $user = User::factory()->withAdminRole()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        // Création d'une catégorie
        $category = Category::factory()->create();

        // Envoi d'une requête pour créer un événemnet
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/events', [
            'title' => 'Discussion sur l\'ia',
            'description' => 'Evénement sur une discussion de l\'ia sur le métier de développeur dans le futur',
            'start_datetime' => '2024-10-22 10:30:00',
            'end_datetime' => '2024-10-22 16:30:00',
            'address' => '11 Rue Jean Nicoli, Corte 20250',
            'capacity' => 60,
            'category_id' => $category->id,
        ]);

        // Vérifier si la réponse correspond à nos exigences
        $response->assertStatus(200)->assertJsonStructure(['success', 'event']);
    }

    // Test pour la récupération
    public function test_get_event(): void
    {
        // Création d'un utilisateur et récupéraiton du token
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        // Création de l'événement
        $event = Event::factory()->create();

        // Envoi d'une requête pour récupérer un événement
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/events/' . $event->id);

        // Vérifier si la réponse correspond à nos exigences
        $response->assertStatus(200)->assertJsonStructure(['success', 'event']);
    }

    // Test pour la modification
    public function test_update_event(): void
    {
        // Création d'un utilisateur et récupéraiton du token
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        // Création d'un événement et d'une catégorie
        $event = Event::factory()->create();
        $category = Category::factory()->create();

        // Envoi d'une requête pour modifier un événement
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson('/api/events/' . $event->id, [
            'title' => 'Discussion sur l\'avenir de l\'ia',
            'description' => 'Evénement sur une discussion de l\'ia sur le métier de développeur dans le futur',
            'start_datetime' => '2024-10-22 10:30:00',
            'end_datetime' => '2024-10-22 16:30:00',
            'address' => '11 Rue Jean Nicoli, Corte 20250',
            'capacity' => 60,
            'category_id' => $category->id,
        ]);

        // Vérifier si la réponse correspond à nos exigences
        $response->assertStatus(200)->assertJsonStructure(['success', 'event']);

        $this->assertDatabaseHas('events', [
            'title' => 'Discussion sur l\'avenir de l\'ia',
        ]);
    }

    // Test pour la suppression
    public function test_delete_event(): void
    {
        // Création d'un utilisateur et récupéraiton du token
        $user = User::factory()->withAdminRole()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        // Création de l'événement
        $event = event::factory()->create();

        // Envoi d'une requête pour supprimer un événement
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson('/api/events/' . $event->id);

        // Vérifier si la réponse correspond à nos exigences
        $response->assertStatus(200)->assertJsonStructure(['success']);
    }
}