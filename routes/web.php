<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ExchangeRateController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/currencies', [ExchangeRateController::class, 'currencies']);

Route::get('/rate', [ExchangeRateController::class, 'rate']);

Route::get('/convert', [ExchangeRateController::class, 'convert']);