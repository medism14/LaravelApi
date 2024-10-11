<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Le mot de passe actuel utilisé par la factory.
     */
    protected static ?string $password;

    /**
     * Définir l'état par défaut du modèle.
     *
     * @return array<string, mixed>
     */
    public function definition($role = 'user'): array
    {
        $roleValue = ($role === 'admin') ? User::ROLE_ADMIN : User::ROLE_USER;

        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => 'password123',
            'role' => $roleValue,
        ];
    }

    /**
     * Générer un token pour l'utilisateur.
     */

    public function withAdminRole(): static
    {
        return $this->afterCreating(function (User $user) {
            $user->role = 0;
            $user->save();
        });
    }
}
