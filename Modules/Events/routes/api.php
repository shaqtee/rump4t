<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
