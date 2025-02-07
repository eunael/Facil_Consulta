<?php

namespace Database\Seeders;

use App\Models\Consultation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConsultationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Consultation::factory(10)->create();
        Consultation::factory()->count(3)->sequence(
            ['date' => now()->subMonth()->toDateString()],
            ['date' => now()->toDateString()],
            ['date' => now()->addMonth()->toDateString()],
        )->create(['doctor_id' => 1]);
    }
}
