<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function doctors()
    {
        $nameToSearch = mb_strtolower(request()->query('nome'));

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
}
