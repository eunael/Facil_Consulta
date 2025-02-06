<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\DoctorController;
use App\Http\Middleware\ApiValidateToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', LoginController::class)->name('api.login');
Route::get('/user', UserController::class)->name('api.user');
Route::get('/cidades', [CityController::class, 'cities'])->name('api.cities');
Route::get('/medicos', [DoctorController::class, 'doctors'])->name('api.doctors');



Route::get('/test', fn() => response()->json(['test' => 'test']))->name('api.test')->middleware(ApiValidateToken::class);
