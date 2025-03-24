<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\MyGames\App\Http\Controllers\MyGamesController;

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
Route::post('lets-play/join', [MyGamesController::class, 'join_match']);
Route::post('lets-play/cancel', [MyGamesController::class, 'cancel_match']);
Route::get('lets-play/list-member-before-join/{id}', [MyGamesController::class, 'indexMemberBeforeJoin'])->middleware('organizedAkses');
Route::patch('lets-play/update-member-before-join/{id}', [MyGamesController::class, 'updateMemberBeforeJoin'])->middleware('organizedAkses');
Route::prefix('lets-play')->group(function() {
    Route::get('', [MyGamesController::class, 'index']);
    Route::post('', [MyGamesController::class, 'store']);
    Route::get('{id}', [MyGamesController::class, 'show']);
    Route::post('{id}', [MyGamesController::class, 'update']);
});
Route::prefix('lets-play-private')->group(function(){
    Route::get('index-player/{id}', [MyGamesController::class, 'indexPlayerSendInvitation'])->middleware('organizedAkses');
    Route::post('send-invitation', [MyGamesController::class, 'sendInvitingPlayers']);
    Route::post('accept-invitation', [MyGamesController::class, 'acceptInviting']);
});
// Route::apiResource('lets-play', MyGamesController::class);

Route::get('my-list-event-play', [MyGamesController::class, 'my_event_play']);
Route::get('my-list-lets-play', [MyGamesController::class, 'my_lets_play']);
Route::get('', [MyGamesController::class, 'my_games']);
Route::post('filter-my-games/{typeGame}', [MyGamesController::class, 'filter']);