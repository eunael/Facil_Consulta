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
}
