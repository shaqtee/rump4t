<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Events\App\Http\Controllers\EventsController;
use Modules\Events\App\Http\Controllers\EventsMemberController;


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

Route::get('/', function (){
    $data = [
        "Module" => "\Events",
    ];

    return response()->json([
        'code' => 200,
        'Massage' => 'Welcome To RUMP4T API Powered By Aksi!',
        'data' => $data,
    ], 200);
});

// event
Route::get('all', [EventsController::class, 'index']);
Route::get('by-region/{region_id}', [EventsController::class, 'index_by_region']);

// event:admin
Route::post('store', [EventsController::class, 'store']);
Route::post('update/{id}', [EventsController::class, 'update']);
Route::post('delete/{id}', [EventsController::class, 'destroy']);

// event:member
Route::post('member-store', [EventsMemberController::class, 'store']);
Route::post('member-delete/{id}', [EventsMemberController::class, 'destroy']);