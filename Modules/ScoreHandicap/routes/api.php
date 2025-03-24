<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\ScoreHandicap\App\Http\Controllers\ScoreHandicapController;

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
        "Module" => "\Score Handicap",
    ];

    return response()->json([
        'code' => 200,
        'Massage' => 'Welcome To DGOLF API Powered By Aksi',
        'data' => $data,
    ], 200);
});

Route::get('my-list-event', [ScoreHandicapController::class, 'my_list_event']);
Route::get('my-list-lets-play', [ScoreHandicapController::class, 'my_list_lets_play']);
Route::get('my-list-all-games', [ScoreHandicapController::class, 'my_list_all_games']);
Route::post('store-score', [ScoreHandicapController::class, 'storeV2']);
Route::post('score-detail', [ScoreHandicapController::class, 'storeScoresDetails']);
Route::post('rumus-handicap-peoria', [ScoreHandicapController::class, 'hitungHandicapPeoria']);
Route::apiResource('sc', ScoreHandicapController::class);
