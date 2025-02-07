<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    public function create(Request $request)
    {
        $data = $request->only(['medico_id', 'paciente_id', 'data']);

        $doctor = Doctor::findOrFail($data['medico_id']);
        $patient = Patient::findOrFail($data['paciente_id']);

        $consultation = Consultation::create([
            'doctor_id' => $doctor->id,
            'patient_id' => $patient->id,
            'date' => $data['data']
        ]);

        return response()->json([
            'medico_id' => $consultation->doctor_id,
            'paciente_id' => $consultation->patient_id,
            'data' => $consultation->date,
        ]);
    }
}
