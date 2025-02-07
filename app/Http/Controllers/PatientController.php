<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function create(Request $request)
    {
        $data = $request->only(['nome', 'cpf', 'celular']);

        return response()->json(
            Patient::create([
                'name' => $data['nome'],
                'cpf' => $data['cpf'],
                'cell' => $data['celular'],
            ])
        );
    }

    public function update(int $id_paciente, Request $request)
    {
        $patient = Patient::findOrFail($id_paciente);

        $data = $request->only(['nome', 'cpf', 'celular']);

        if(isset($data['nome'])) $patient->name = $data['nome'];
        if(isset($data['cpf'])) $patient->cpf = $data['cpf'];
        if(isset($data['celular'])) $patient->cell = $data['celular'];

        $patient->save();

        return response()->json(
            $patient->fresh()->toArray()
        );
    }
}
