<?php
namespace Tests\Feature;

use App\Models\City;
use App\Models\Doctor;

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

test('unauthorized user should not access list of doctors cities', function () {
    $city = City::factory()->create();

    $this->get(route('api.cities.doctors', ['id_cidade' => $city->id], true))
        ->assertUnauthorized()
        ->assertJson(['error' => 'Authorization token is not found']);
});

it('should list all doctors from city', function () {
    $token = getToken();

    $city = City::factory()
        ->has(Doctor::factory()->count(3))
        ->create();

    $doctorsFromCity = $city->doctors;

    Doctor::factory(3)->create();

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token
    ])
        ->get(route('api.cities.doctors', ['id_cidade' => $city->id], true));
    $response->assertOk();

    expect($response->getData())
        ->toMatchArray(json_decode($doctorsFromCity->toJson()));
});

it('should list all doctors from city and seach doctors by name', function () {
    $token = getToken();

    $city = City::factory()
        ->has(Doctor::factory()->count(1)->sequence(['name' => 'Teste']))
        ->create();

    $doctorsFromCitySearchByName = Doctor::factory(2)->create([
        'name' => 'Lil ' . fake()->name,
        'city_id' => $city->id,
    ]);

    Doctor::factory(3)->create();

    $nameToSearch = 'lil';
    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token
    ])
        ->get(route('api.cities.doctors', ['id_cidade' => $city->id, 'nome' => $nameToSearch], true));
    $responseDr = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token
    ])
        ->get(route('api.cities.doctors', ['id_cidade' => $city->id, 'nome' => 'dr ' . $nameToSearch], true));

    $response->assertOk();
    $data = $response->getData();
    expect(count($data))
        ->toBe(2)
        ->and($data)
        ->toMatchArray(json_decode($doctorsFromCitySearchByName->toJson()));

    $responseDr->assertOk();
    $dataDr = $responseDr->getData();
    expect(count($dataDr))
        ->toBe(2)
        ->and($dataDr)
        ->toMatchArray(json_decode($doctorsFromCitySearchByName->toJson()));
});
