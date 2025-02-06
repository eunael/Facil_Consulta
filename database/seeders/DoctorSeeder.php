<?php

namespace Database\Seeders;

use App\Models\Doctor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Doctor::factory(4)->create();
        Doctor::factory(6)
            ->sequence(
                ['name' => 'Lil ' . ucfirst(fake()->name)],
                ['name' => ucfirst(fake()->name) . 'lil ' . ucfirst(fake()->name)],
                ['name' => ucfirst(fake()->name) . 'líl ' . ucfirst(fake()->name)],
            )
            ->create();
        Doctor::factory(4)->create(['city_id' => 1]);
    }
}
