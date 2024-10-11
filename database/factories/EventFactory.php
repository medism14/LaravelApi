<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */

class EventFactory extends Factory
{
    /**
     * Définir l'état par défaut du modèle.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {   
        $startDateTime = $this->faker->dateTimeBetween('now', '+1 year');
        $endDateTime = clone $startDateTime;
        $endDateTime->modify('+' . $this->faker->numberBetween(1, 8) . ' hours');

        $capacity = $this->faker->numberBetween(30, 1000);

        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->text(50),
            'start_datetime' => $startDateTime,
            'end_datetime' => $endDateTime,
            'address' => $this->faker->address,
            'capacity' => $capacity,
            'remainingPlaces' => $capacity,
            'category_id' => Category::factory(),
        ];
    }
}
