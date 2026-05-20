<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExchangeRateController;

// Main routes
Route::get('/', [ExchangeRateController::class, 'dashboard']);
Route::get('/dashboard', [ExchangeRateController::class, 'dashboard']);

// Currency routes
Route::get('/currencies', [ExchangeRateController::class, 'currencies']);
Route::post('/favorite/add', [ExchangeRateController::class, 'addFavorite']);
Route::delete('/favorite/remove/{code}', [ExchangeRateController::class, 'removeFavorite']);
Route::post('/favorite/reorder', [ExchangeRateController::class, 'reorderFavorites']);

// Rate routes
Route::get('/rate', [ExchangeRateController::class, 'rate']);
Route::get('/compare', [ExchangeRateController::class, 'compare']);

// Converter routes
Route::match(['get', 'post'], '/convert', [ExchangeRateController::class, 'convert']);

// Alert routes
Route::post('/alert/create', [ExchangeRateController::class, 'createAlert']);
Route::delete('/alert/delete/{id}', [ExchangeRateController::class, 'deleteAlert']);
Route::get('/alerts', [ExchangeRateController::class, 'getAlerts']);

// Export & History
Route::get('/export/history', [ExchangeRateController::class, 'exportHistory']);
Route::get('/clear-history', [ExchangeRateController::class, 'clearHistory']);

// API Routes (AJAX)
Route::get('/api/live-rate', [ExchangeRateController::class, 'liveRate']);
Route::get('/api/live-rates', [ExchangeRateController::class, 'liveRatesMultiple']);