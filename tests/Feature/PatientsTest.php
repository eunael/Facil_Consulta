<?php
namespace Tests\Feature;

use App\Models\Patient;

it('should create a new patients', function () {
    $patient = Patient::factory()->make();
    $this->post(route('api.patients.create', absolute: true), ['nome' => $patient->name, 'cpf' => $patient->cpf, 'celular' => $patient->cell])
        ->assertUnauthorized()
        ->assertJson(['error' => 'Authorization token is not found']);

    $token = getToken();

    $this->withHeaders(['Authorization' => 'Bearer ' . $token])
        ->post(
            route('api.patients.create', absolute: true),
            ['nome' => $patient->name, 'cpf' => $patient->cpf, 'celular' => $patient->cell]
        )
            ->assertOk()
            ->assertJsonStructure([
                'name',
                'cpf',
                'cell',
            ]);
})->only();
