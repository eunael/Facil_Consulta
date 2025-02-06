<?php
namespace Tests\Feature;

use App\Models\Doctor;

it('should list all doctors', function () {
    Doctor::factory(10)->create();

    $response = $this->get(route('api.doctors', absolute: true))
        ->assertOk();

    expect($response->getData())
        ->toMatchArray(json_decode(Doctor::all()->toJson()));
});

it('should search doctors by name', function () {
    Doctor::factory(8)
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

    $nameToSearch = 'lil';
    $response = $this->get(route('api.doctors', ['nome' => $nameToSearch], true))
        ->assertOk();

    $doctors = Doctor::query()
        ->when(
            $nameToSearch !== null,
            function($q) use ($nameToSearch) {
                return $q->cursor() // usar o cursor p/ economizar mémoria durante o filter
                    ->filter(
                        fn($doctor) =>
                            str_contains(removeAccents(mb_strtolower($doctor->name)), $nameToSearch)
                    );
            }
        )
        ->values()
        ->toJson();

    $data = $response->getData();

    expect(count($data))
        ->toBe(3)
        ->and($data)
        ->toMatchArray(json_decode($doctors));
});
