<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function cities()
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
}
