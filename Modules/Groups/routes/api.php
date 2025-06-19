<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Groups\App\Http\Controllers\GroupsController;
use Modules\Groups\App\Http\Controllers\PostingController;
use Modules\Groups\App\Http\Controllers\PostingModerationController;

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
            Route::get('{group_id}',[GroupsController::class, 'index_member']);
            Route::post('add',[GroupsController::class, 'add_member']);
            Route::delete('left',[GroupsController::class, 'left_member']);
            Route::patch('be-admin',[GroupsController::class, 'be_admin']);
        });

        Route::prefix('posting')->group(function() {
            /* Posting */
            Route::get('{group_id}',[PostingController::class, 'index_posts']);
            Route::post('store/{group_id}',[PostingController::class, 'store']);
            Route::get('{group_id}/{id}', [PostingController::class, 'show']);
            Route::post('update/{group_id}/{id}', [PostingController::class, 'update']);
            Route::delete('delete/{group_id}/{id}', [PostingController::class, 'destroy']);

            Route::prefix('{group_id}/{post_id}')->group(function() {
                Route::put('moderate', [PostingModerationController::class, 'moderate']);
                Route::get('comments', [PostingModerationController::class, 'listComments']);
                Route::post('comments', [PostingModerationController::class, 'storeComment']);
                Route::put('comments/{comment_id}', [PostingModerationController::class, 'updateComment']);
                Route::delete('comments/{comment_id}', [PostingModerationController::class, 'destroyComment']);
                Route::get('comments/{comment_id}/replies', [PostingModerationController::class, 'listSubcomments']);
                Route::post('comments/{comment_id}/replies', [PostingModerationController::class, 'storeSubcomment']);
                Route::put('comments/{comment_id}/replies/{subcomment_id}', [PostingModerationController::class, 'updateSubcomment']);
                Route::delete('comments/{comment_id}/replies/{subcomment_id}', [PostingModerationController::class, 'destroySubcomment']);
            });

        });
    });
});
