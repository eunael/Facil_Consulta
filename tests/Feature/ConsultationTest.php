<?php
namespace Tests\Feature;

use App\Models\Consultation;
use App\Models\Patient;

it('should create a new consultation', function () {
    $consultation = Consultation::factory()->make();

    $this->post(route('api.doctor.consultation.create', absolute: true), ['medico_id' => $consultation->doctor_id, 'paciente_id' => $consultation->patient_id, 'data' => $consultation->date])
        ->assertUnauthorized()
        ->assertJson(['error' => 'Authorization token is not found']);

    $token = getToken();

    $this->withHeaders(['Authorization' => 'Bearer ' . $token])
        ->post(
            route('api.doctor.consultation.create', absolute: true),
            ['medico_id' => $consultation->doctor_id, 'paciente_id' => $consultation->patient_id, 'data' => $consultation->date]
        )
            ->assertOk()
            ->assertJsonStructure([
                'medico_id',
                'paciente_id',
                'data',
            ]);

    $this->assertDatabaseHas(Consultation::class, [
        'doctor_id' => $consultation->doctor_id,
        'patient_id' => $consultation->patient_id,
        'date' => $consultation->date,
    ]);
});
