<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Pemilu\App\Http\Controllers\PemiluController;
use Modules\Pemilu\App\Http\Controllers\CandidateController;
use Modules\Pemilu\App\Http\Controllers\PollingsController;

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

Route::middleware('auth:api')->group(function () {
    Route::prefix('pemilu')->group(function() {
        /* CRUD */
        Route::get('/',[PemiluController::class, 'index']);
        Route::post('store',[PemiluController::class, 'store']);
        Route::patch('update/{pemilu}',[PemiluController::class, 'update']);
        Route::delete('delete/{id}',[PemiluController::class, 'destroy']);

        /* Kandidat */
        Route::prefix('candidate')->group(function() {
            Route::get('{pemilu_id}', [CandidateController::class ,'index']);
            Route::post('add', [CandidateController::class ,'add']);
            Route::delete('{candidate}/left', [CandidateController::class ,'left']);
            Route::patch('{candidate}/activate', [CandidateController::class ,'activate']);
        });

        /* Pollings */
        Route::prefix('pollings')->group(function() {
            Route::get('/', [PollingsController::class ,'index']);
            Route::get('pre-vote-users', [PollingsController::class ,'pre_vote_users']);
            Route::post('votes', [PollingsController::class ,'votes']);
            Route::get('index-voted/{pemilu_id}', [PollingsController::class ,'index_voted']);
            Route::delete('cancel-voted/{voted_id}', [PollingsController::class ,'cancel_voted']);
        });
    });
});