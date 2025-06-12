<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Groups\App\Http\Controllers\GroupsController;
use Modules\Groups\App\Http\Controllers\PostingController;

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
    Route::prefix('groups')->group(function() {
        /* CRUD */
        Route::get('/',[GroupsController::class, 'index']);
        Route::post('store',[GroupsController::class, 'store']);
        Route::post('update/{id}',[GroupsController::class, 'update']);
        Route::delete('delete/{id}',[GroupsController::class, 'destroy']);

        Route::prefix('member')->group(function() {
            /* Member */
            Route::get('/{group_id}',[GroupsController::class, 'index_member']);
            Route::post('add',[GroupsController::class, 'add_member']);
            Route::delete('left',[GroupsController::class, 'left_member']);
            Route::patch('be-admin',[GroupsController::class, 'be_admin']);
        });

        Route::prefix('posting')->group(function() {
            /* Posting */
            Route::get('/{group_id}',[PostingController::class, 'index_posting']);
        });
    });
});
