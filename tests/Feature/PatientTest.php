<?php
namespace Tests\Feature;

use App\Models\Patient;

it('should create a new patient', function () {
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

    $this->assertDatabaseHas(Patient::class, [
        'name' => $patient->name,
        'cpf' => $patient->cpf,
        'cell' => $patient->cell,
    ]);
});

it('should edit a patient', function () {
    $patientMock = Patient::factory()->make();
    $patient = Patient::factory()->create();
    $this->put(route('api.patients.update', ['id_paciente' => $patient->id], true), ['nome' => $patientMock->name, 'cpf' => $patientMock->cpf, 'celular' => $patientMock->cell])
        ->assertUnauthorized()
        ->assertJson(['error' => 'Authorization token is not found']);

    $token = getToken();

    $this->withHeaders(['Authorization' => 'Bearer ' . $token])
        ->put(
            route('api.patients.update', ['id_paciente' => $patient->id], true),
            ['nome' => $patientMock->name, 'cpf' => $patientMock->cpf, 'celular' => $patientMock->cell]
        )
            ->assertOk()
            ->assertJsonStructure([
                'name',
                'cpf',
                'cell',
            ]);

    $this->assertDatabaseHas(Patient::class, [
        'id' => $patient->id,
        'name' => $patientMock->name,
        'cpf' => $patientMock->cpf,
        'cell' => $patientMock->cell,
    ]);
});
