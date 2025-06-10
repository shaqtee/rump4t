<?php

use Illuminate\Support\Facades\Route;
use Modules\Pemilu\App\Http\Controllers\PemiluController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group([], function () {
    Route::resource('pemilu', PemiluController::class)->names('pemilu');
});
