<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Event;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Définir l'état par défaut du modèle.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {   
        return [
            'status' => $this->faker->randomElement(['reserved', 'waiting']),
            'number_of_seat' => $this->faker->numberBetween(1, 4),
            'user_id' => User::factory(),
            'event_id' => Event::factory(),
        ];
    }
}
