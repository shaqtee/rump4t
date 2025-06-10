<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Donasi\App\Http\Controllers\DonasiController;

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

Route::get('/', [DonasiController::class, 'index']);
Route::post('/store', [DonasiController::class, 'store']);