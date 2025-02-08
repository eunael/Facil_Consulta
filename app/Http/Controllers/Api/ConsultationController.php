<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    public function create(Request $request): JsonResponse
    {
        $data = $request->only(['medico_id', 'paciente_id', 'data']);

        $doctor = Doctor::findOrFail($data['medico_id']);
        $patient = Patient::findOrFail($data['paciente_id']);

        return response()->json(
            Consultation::create([
                'doctor_id' => $doctor->id,
                'patient_id' => $patient->id,
                'date' => $data['data']
            ])
        );
    }
}
