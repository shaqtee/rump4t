<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Performace\App\Http\Controllers\PerformaceController;

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
Route::get('', [PerformaceController::class, 'box']);
Route::get('index', [PerformaceController::class, 'index']);
Route::get('view-all', [PerformaceController::class, 'viewAll']);
