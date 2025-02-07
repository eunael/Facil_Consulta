<?php
namespace Tests\Feature;

use App\Models\City;
use App\Models\Consultation;
use App\Models\Doctor;
use App\Models\Patient;
use Carbon\Carbon;

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
    $responseDr = $this->get(route('api.doctors', ['nome' => 'dr ' . $nameToSearch], true))
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

    $dataDr = $responseDr->getData();
    expect(count($dataDr))
        ->toBe(3)
        ->and($dataDr)
        ->toMatchArray(json_decode($doctors));
});

it('should create a new doctor', function () {
    $doctor = Doctor::factory()->make();

    $this->post(route('api.doctors.create', absolute: true), ['nome' => $doctor->name,'especialidade' => $doctor->specialty,'cidade_id' => $doctor->city_id])
        ->assertUnauthorized()
        ->assertJson(['error' => 'Authorization token is not found']);

    $token = getToken();

    $this->withHeaders(['Authorization' => 'Bearer ' . $token])
        ->post(
            route('api.doctors.create', absolute: true),
            ['nome' => $doctor->name,'especialidade' => $doctor->specialty,'cidade_id' => $doctor->city_id]
        )
            ->assertOk()
            ->assertJsonStructure([
                'name',
                'specialty',
                'city_id',
            ]);
});

it('should list all doctor\'s  patients', function() {
    $doctor = Doctor::factory()
        ->has(Consultation::factory(3))
        ->create();

    $this->get(route('api.doctors.patients', ['id_medico' => $doctor->id], true))
        ->assertUnauthorized()
        ->assertJson(['error' => 'Authorization token is not found']);

    $token = getToken();

    $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
        ->get(route('api.doctors.patients', ['id_medico' => $doctor->id], true));
    $response->assertOk();

    $doctorPatientsAndConsultations = $doctor->patients->sortBy(function ($patient) {
        $date = Carbon::parse($patient->pivot->date);
        return $date->getTimestamp();
    })
        ->values()
        ->toJson();

    expect($response->getData())
        ->toMatchArray(json_decode($doctorPatientsAndConsultations));
});

it('should search doctor\'s patients by name', function() {
    $patients = Patient::factory()->count(3)->sequence(
        ['name' => 'Ana Alice'],
        ['name' => 'Mariana'],
        ['name' => 'Liliane'],
    )->create();
    $doctor = Doctor::factory()
        ->has(Consultation::factory()->count(3)->sequence(
            ['patient_id' => $patients->get(0)->id],
            ['patient_id' => $patients->get(1)->id],
            ['patient_id' => $patients->get(2)->id],
        ))
        ->create();

    $token = getToken();

    $nameToSearch = 'ana';

    $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
        ->get(route('api.doctors.patients', ['id_medico' => $doctor->id, 'nome' => $nameToSearch], true));
    $response->assertOk();

    $doctorPatientsAndConsultations = $doctor->patients()
        ->cursor() // usar o cursor p/ economizar mémoria durante o filter
        ->filter(
            fn($patient) =>
                str_contains(removeAccents(mb_strtolower($patient->name)), $nameToSearch)
        )
        ->sortBy(function ($patient) {
            $date = Carbon::parse($patient->pivot->date);
            return $date->getTimestamp();
        })
        ->values()
        ->toJson();

    expect($response->getData())
        ->toMatchArray(json_decode($doctorPatientsAndConsultations));
});

it('should list only scheduled doctor\'s  patients', function() {
    $doctor = Doctor::factory()
        ->has(Consultation::factory()->count(5)->sequence(
            ['date' => now()->addMonths(2)->toDateString()],
            ['date' => now()->subMonth()->toDateString()],
            ['date' => now()->toDateString()],
            ['date' => now()->addDay()->toDateString()],
            ['date' => now()->addMonth()->toDateString()],
        ))
        ->create();

    $this->get(route('api.doctors.patients', ['id_medico' => $doctor->id], true))
        ->assertUnauthorized()
        ->assertJson(['error' => 'Authorization token is not found']);

    $token = getToken();

    $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
        ->get(route('api.doctors.patients', ['id_medico' => $doctor->id, 'apenas-agendadas' => 'true'], true));
    $response->assertOk();

    $doctorPatientsAndConsultations = $doctor->patients()->whereHas('consultations', function($q) {
        return $q->where('date', '>=', now()->toDateString());
    })
        ->get()
        ->sortBy(function ($patient) {
            $date = Carbon::parse($patient->pivot->date);
            return $date->getTimestamp();
        })
        ->values();

    expect($response->getData())
        ->toMatchArray(json_decode($doctorPatientsAndConsultations));
});
