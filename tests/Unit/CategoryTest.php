<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{

    use RefreshDatabase;

    // Test de la récupération de tout
    public function test_get_all_categories(): void
    {
        // Création d'un utilisateur et récupéraiton du token
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        // Envoi d'une requête pour récupérer toutes les catégories
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/categories');

        // Vérifier si la réponse correspond à nos exigences
        $response->assertStatus(200)->assertJsonStructure(['categories']);
    }

    // Test pour la création 
    public function test_create_category(): void
    {
        // Création d'un utilisateur et récupéraiton du token
        $user = User::factory()->withAdminRole()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        // Envoi d'une requête pour créer une catégorie
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/categories', [
            'name' => 'Atelier',
        ]);

        // Vérifier si la réponse correspond à nos exigences
        $response->assertStatus(200)->assertJsonStructure(['success', 'category']);
    }

    // Test pour la récupération
    public function test_get_category(): void
    {
        // Création d'un utilisateur et récupéraiton du token
        $user = User::factory()->withAdminRole()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        // Création de la catégorie
        $category = Category::factory()->create();

        // Envoi d'une requête pour récupérer une catégorie
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/categories/' . $category->id);

        // Vérifier si la réponse correspond à nos exigences
        $response->assertStatus(200)->assertJsonStructure(['success', 'category']);
    }

    // Test pour la modification
    public function test_update_category(): void
    {
        // Création d'un utilisateur et récupéraiton du token
        $user = User::factory()->withAdminRole()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        // Création de la catégorie
        $category = Category::factory()->create();

        // Envoi d'une requête pour modifier une catégorie
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson('/api/categories/' . $category->id, [
            'name' => 'Conférence universelle'
        ]);

        // Vérifier si la réponse correspond à nos exigences
        $response->assertStatus(200)->assertJsonStructure(['success', 'category']);

        $this->assertDatabaseHas('categories', [
            'name' => 'Conférence universelle'
        ]);
    }

    // Test pour la suppression
    public function test_delete_category(): void
    {
        // Création d'un utilisateur et récupéraiton du token
        $user = User::factory()->withAdminRole()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        // Création de la catégorie
        $category = Category::factory()->create();

        // Envoi d'une requête pour supprimer une catégorie
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson('/api/categories/' . $category->id);

        // Vérifier si la réponse correspond à nos exigences
        $response->assertStatus(200)->assertJsonStructure(['success']);
    }
}
