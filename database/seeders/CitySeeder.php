<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        City::factory(15)->create();
        City::factory(15)
            ->sequence(
                ['name' => 'São ' . ucfirst(fake()->word)],
                ['name' => ucfirst(fake()->word) . 'são ' . ucfirst(fake()->word)],
                ['name' => 'Sao' . fake()->word],
            )
            ->create();
    }
}
