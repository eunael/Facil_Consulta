<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function patients()
    {
        $nameToSearch = removeDrDra(mb_strtolower(request()->query('nome')));

        $patients = Patient::query()
            ->when(
                $nameToSearch !== null,
                function($q) use ($nameToSearch) {
                    return $q->cursor()
                        ->filter(
                            fn($patient) =>
                                str_contains(removeAccents(mb_strtolower($patient->name)), $nameToSearch)
                        );
                }
            )
            ->values()
            ->toArray();

        return response()->json($patients);
    }

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
