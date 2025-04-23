<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\News\App\Http\Controllers\NewsController;

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

Route::middleware(["auth:api"])->prefix("news")->group(function (){
    Route::get('', [NewsController::class, 'index'])->name('news.index');

});
