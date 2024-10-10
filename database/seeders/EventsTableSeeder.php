<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Event;


class EventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Event::create([
            'title' => 'Conférence sur l\'IA',
            'description' => 'Une conférence sur les dernières avancées en intelligence artificielle.',
            'start_datetime' => '2024-11-01 10:00:00',
            'end_datetime' => '2024-11-01 12:00:00',
            'address' => '123 Rue de l\'Innovation, Paris',
            'capacity' => 100,
            'remainingPlaces' => 100,
            'category_id' => 1,
        ]);

        Event::create([
            'title' => 'Atelier de développement web',
            'description' => 'Un atelier pratique sur le développement web moderne.',
            'start_datetime' => '2024-11-05 14:00:00',
            'end_datetime' => '2024-11-05 17:00:00',
            'address' => '456 Avenue du Code, Lyon',
            'capacity' => 50,
            'remainingPlaces' => 50,
            'category_id' => 2,
        ]);

        Event::create([
            'title' => 'Séminaire sur la cybersécurité',
            'description' => 'Un séminaire sur les meilleures pratiques en cybersécurité.',
            'start_datetime' => '2024-11-10 09:00:00',
            'end_datetime' => '2024-11-10 11:00:00',
            'address' => '789 Boulevard de la Sécurité, Marseille',
            'capacity' => 75,
            'remainingPlaces' => 75,
            'category_id' => 4,
        ]);

        Event::create([
            'title' => 'Webinaire sur le marketing digital',
            'description' => 'Un webinaire sur les stratégies de marketing digital.',
            'start_datetime' => '2024-11-15 15:00:00',
            'end_datetime' => '2024-11-15 16:30:00',
            'address' => 'En ligne',
            'capacity' => 200,
            'remainingPlaces' => 200,
            'category_id' => 5,
        ]);

        Event::create([
            'title' => 'Rencontre des développeurs',
            'description' => 'Une rencontre pour échanger sur les nouvelles technologies.',
            'start_datetime' => '2024-11-20 18:00:00',
            'end_datetime' => '2024-11-20 20:00:00',
            'address' => '321 Rue des Développeurs, Toulouse',
            'capacity' => 30,
            'remainingPlaces' => 30,
            'category_id' => 3,
        ]);

        Event::create([
            'title' => 'Atelier de photographie',
            'description' => 'Un atelier pour apprendre les bases de la photographie.',
            'start_datetime' => '2024-11-25 10:00:00',
            'end_datetime' => '2024-11-25 13:00:00',
            'address' => '654 Avenue de la Photo, Nice',
            'capacity' => 20,
            'remainingPlaces' => 20,
            'category_id' => 2,
        ]);

        Event::create([
            'title' => 'Séminaire sur la gestion de projet',
            'description' => 'Un séminaire sur les meilleures pratiques en gestion de projet.',
            'start_datetime' => '2024-11-30 09:00:00',
            'end_datetime' => '2024-11-30 12:00:00',
            'address' => '987 Boulevard de la Gestion, Bordeaux',
            'capacity' => 60,
            'remainingPlaces' => 60,
            'category_id' => 4,
        ]);

        Event::create([
            'title' => 'Conférence sur l\'entrepreneuriat',
            'description' => 'Une conférence sur les défis et opportunités de l\'entrepreneuriat.',
            'start_datetime' => '2024-12-05 14:00:00',
            'end_datetime' => '2024-12-05 16:00:00',
            'address' => '159 Rue de l\'Entrepreneuriat, Lille',
            'capacity' => 150,
            'remainingPlaces' => 150,
            'category_id' => 1,
        ]);
    }
}
