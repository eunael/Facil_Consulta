<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\ConsultationController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\PatientController;
use App\Http\Middleware\ApiValidateToken;
use Illuminate\Support\Facades\Route;

Route::post('/login', LoginController::class)->name('api.login');
Route::get('/user', UserController::class)->name('api.user');
Route::get('/cidades', [CityController::class, 'cities'])->name('api.cities');
Route::get('/medicos', [DoctorController::class, 'doctors'])->name('api.doctors');

Route::middleware([ApiValidateToken::class])->group(function() {
    Route::get('/cidades/{id_cidade}/medicos', [CityController::class, 'doctorsFromCity'])->name('api.cities.doctors');

    Route::prefix('/pacientes')->group(function() {
        Route::post('/', [PatientController::class, 'create'])->name('api.patients.create');
        Route::put('/{id_paciente}', [PatientController::class, 'update'])->name('api.patients.update');
    });

    Route::prefix('/medicos')->group(function() {
        Route::post('/', [DoctorController::class, 'create'])->name('api.doctors.create');

        Route::post('/consulta', [ConsultationController::class, 'create'])->name('api.doctor.consultation.create');

        Route::get('/{id_medico}/pacientes', [DoctorController::class, 'patients'])->name('api.doctors.patients');
    });
});



Route::get('/test', fn() => response()->json(['test' => 'test']))->name('api.test')->middleware(ApiValidateToken::class);
