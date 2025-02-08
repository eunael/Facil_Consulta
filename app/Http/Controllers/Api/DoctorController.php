<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function doctors(): JsonResponse
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

    public function create(Request $request): JsonResponse
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

    public function patients(int $id_medico): JsonResponse
    {
        $nameToSearch = request()->query('nome');
        $onlyScheduleds = request()->query('apenas-agendadas') === 'true';

        $doctor = Doctor::findOrFail($id_medico);

        $patientsStatement = $doctor->patients()
            ->when(
                $onlyScheduleds,
                fn($patients) => $patients->whereHas('consultations', function($q) {
                    return $q->where('date', '>=', now()->toDateString());
                })
            );

        if($nameToSearch !== null) {
            $patientsStatement = $patientsStatement->cursor()
                ->filter(
                    fn($patient) =>
                        str_contains(removeAccents(mb_strtolower($patient->name)), $nameToSearch)
                );
        }

        $patients = $patientsStatement instanceof BelongsToMany ?
            $patientsStatement->get():
            $patientsStatement;

        return response()->json(
            $patients
                ->sortBy(function ($patient) {
                    $date = Carbon::parse($patient->pivot->date);
                    return $date->getTimestamp();
                })
                    ->values()
                    ->toArray()
        );
    }
}
