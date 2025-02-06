<?php
namespace Tests\Feature;

use App\Models\City;

it('should list all cities', function () {
    City::factory(10)->create();

    $response = $this->get(route('api.cities', absolute: true))
        ->assertOk();

    expect($response->getData())
        ->toMatchArray(json_decode(City::all()->toJson()));
});

it('should search cities by name', function () {
    City::factory(10)
        ->sequence(
            ['name' => 'São' . fake()->word],
            ['name' => ucfirst(fake()->word ). 'sao' . fake()->word()],
            ['name' => 'Pa'],
            ['name' => 'Pe'],
            ['name' => 'Pi'],
            ['name' => 'Po'],
            ['name' => 'Pu'],
            ['name' => 'P0'],
        )
        ->create();

    $nameToSearch = 'sao';
    $response = $this->get(route('api.cities', ['nome' => $nameToSearch], true))
        ->assertOk();

    $cities = City::query()
        ->when(
            $nameToSearch !== null,
            function($q) use ($nameToSearch) {
                return $q->cursor() // usar o cursor p/ economizar mémoria durante o filter
                    ->filter(
                        fn($city) =>
                            str_contains(removeAccents(mb_strtolower($city->name)), $nameToSearch)
                    );
            }
        )
        ->values()
        ->toJson();

    $data = $response->getData();

    expect(count($data))
        ->toBe(4)
        ->and($data)
        ->toMatchArray(json_decode($cities));
});
