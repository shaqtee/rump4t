<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Polling\App\Http\Controllers\PollingController;

/*
    |--------------------------------------------------------------------------
    | API Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register API routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | is assigned the "api" middleware group. Enjoy building your API!
    |
*/

// Route::middleware(['auth'])->prefix('polling')->group(function () {
//     Route::get('/me', fn (Request $request) => $request->user());
//     Route::get('/', [PollingController::class, 'index']); // untuk list polling (aktif, tidak aktif, atau detail)
//     Route::post('/vote', [PollingController::class, 'submit_vote']); 
// });

Route::prefix('polling')->group(function () {
    Route::get('/', [PollingController::class, 'index']); // untuk list polling (aktif, tidak aktif, atau detail)
    Route::post('/vote', [PollingController::class, 'submit_vote']); 
});