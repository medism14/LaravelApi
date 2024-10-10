<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Info: J'ai mis un casts dans users pour crypter automatiquement le mot de passe
        User::create([
            "name" => "Med Ismael",
            "email" => "medismael14@gmail.com",
            "password" => "password123"
        ]);

        User::create([
            "name" => "Jean",
            "email" => "jean@gmail.com",
            "password" => "password123"
        ]);

        User::create([
            "name" => "Marie",
            "email" => "marie@gmail.com",
            "password" => "password123"
        ]);

        User::create([
            "name" => "Pierre",
            "email" => "pierre@gmail.com",
            "password" => "password123"
        ]);

        User::create([
            "name" => "Sophie",
            "email" => "sophie@gmail.com",
            "password" => "password123"
        ]);

        User::create([
            "name" => "Lucas",
            "email" => "lucas@gmail.com",
            "password" => "password123"
        ]);

        User::create([
            "name" => "Emma",
            "email" => "emma@gmail.com",
            "password" => "password123"
        ]);
    }
}
