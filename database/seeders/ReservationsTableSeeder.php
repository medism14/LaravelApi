<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Reservation;

class ReservationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Reservation::create([
            'status' => 'reserved',
            'number_of_seat' => 2,
            'user_id' => 1,
            'event_id' => 1,
        ]);

        Reservation::create([
            'status' => 'cancelled',
            'number_of_seat' => 1,
            'user_id' => 2,
            'event_id' => 2,
        ]);

        Reservation::create([
            'status' => 'reserved',
            'number_of_seat' => 3,
            'user_id' => 3,
            'event_id' => 3,
        ]);

        Reservation::create([
            'status' => 'cancelled',
            'number_of_seat' => 2,
            'user_id' => 4,
            'event_id' => 4,
        ]);

        Reservation::create([
            'status' => 'reserved',
            'number_of_seat' => 1,
            'user_id' => 5,
            'event_id' => 5,
        ]);
    }
}
