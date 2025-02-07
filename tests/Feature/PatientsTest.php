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
});

it('should list all patients', function () {
    Patient::factory(10)->create();

    $this->post(route('api.patients', absolute: true))
        ->assertUnauthorized()
        ->assertJson(['error' => 'Authorization token is not found']);

    $token = getToken();

    $response = $this
        ->withHeaders(['Authorization' => 'Bearer ' . $token])
        ->get(route('api.patients', absolute: true))
        ->assertOk();

    expect($response->getData())
        ->toMatchArray(json_decode(Patient::all()->toJson()));
});

it('should search patients by name', function () {
    Patient::factory(8)
        ->sequence(
            ['name' => 'Lil' . fake()->word],
            ['name' => ucfirst(fake()->word ). 'lil' . fake()->word()],
            ['name' => ucfirst(fake()->word ). 'líl' . fake()->word()],
            ['name' => 'Pa'],
            ['name' => 'Pe'],
            ['name' => 'Pi'],
            ['name' => 'Po'],
            ['name' => 'Pu'],
        )
        ->create();

    $token = getToken();

    $nameToSearch = 'lil';
    $response = $this
        ->withHeaders(['Authorization' => 'Bearer ' . $token])
        ->get(route('api.patients', ['nome' => $nameToSearch], true))
        ->assertOk();

    $patients = Patient::query()
        ->when(
            $nameToSearch !== null,
            function($q) use ($nameToSearch) {
                return $q->cursor() // usar o cursor p/ economizar mémoria durante o filter
                    ->filter(
                        fn($patient) =>
                            str_contains(removeAccents(mb_strtolower($patient->name)), $nameToSearch)
                    );
            }
        )
        ->values()
        ->toJson();

    $data = $response->getData();
    expect(count($data))
        ->toBe(3)
        ->and($data)
        ->toMatchArray(json_decode($patients));
})->only();
