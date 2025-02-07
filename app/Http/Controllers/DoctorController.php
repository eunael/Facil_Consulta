<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function doctors()
    {
        $nameToSearch = removeDrDra(mb_strtolower(request()->query('nome')));

        $doctors = Doctor::query()
            ->when(
                $nameToSearch !== null,
                function($q) use ($nameToSearch) {
                    return $q->cursor()
                        ->filter(
                            fn($doctor) =>
                                str_contains(removeAccents(mb_strtolower($doctor->name)), $nameToSearch)
                        );
                }
            )
            ->values()
            ->toArray();

        return response()->json($doctors);
    }

    public function create(Request $request)
    {
        $data = $request->only(['nome', 'especialidade', 'cidade_id']);

        return response()->json(
            Doctor::create([
                'name' => $data['nome'],
                'specialty' => $data['especialidade'],
                'city_id' => $data['cidade_id'],
            ])
        );
    }
}
