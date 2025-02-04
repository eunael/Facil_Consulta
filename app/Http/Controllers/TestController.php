<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use function Pest\Laravel\json;

class TestController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return ['teste' => 'oi'];
    }
}
