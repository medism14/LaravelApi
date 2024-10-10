<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'Conférence'
        ]);
        Category::create([
            'name' => 'Atelier'
        ]);
        Category::create([
            'name' => 'Rencontre'
        ]);
        Category::create([
            'name' => 'Séminaire'
        ]);
        Category::create([
            'name' => 'Webinaire'
        ]);
    }
}
