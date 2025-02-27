<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Doctor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function cities(): JsonResponse
    {
        $nameToSearch = mb_strtolower(request()->query('nome'));

        $cities = City::query()
            ->when(
                $nameToSearch !== null,
                function($q) use ($nameToSearch) {
                    return $q->cursor()
                        ->filter(
                            fn($city) =>
                                str_contains(removeAccents(mb_strtolower($city->name)), $nameToSearch)
                        );
                }
            )
            ->values()
            ->toArray();

        return response()->json($cities);
    }

    public function doctorsFromCity(int $id_cidade): JsonResponse
    {
        $nameToSearch = removeDrDra(mb_strtolower(request()->query('nome')));

        $doctorsFromCity = Doctor::query()
            ->where('city_id', $id_cidade)
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

        return response()->json($doctorsFromCity);
    }
}
