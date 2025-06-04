<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Community\App\Http\Controllers\AlbumCommonityController;
use Modules\Community\App\Http\Controllers\CommunityController;
use Modules\Community\App\Http\Controllers\EventCommonityController;
use Modules\Community\App\Http\Controllers\PhotoCommonityController;
use Modules\Community\App\Http\Controllers\PostingCommonityController;
use Modules\Community\App\Http\Controllers\SponsorCommonityController;

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
        "Module" => "\Community",
    ];

    return response()->json([
        'code' => 200,
        'Massage' => 'Welcome To DGOLF API Powered By Aksi',
        'data' => $data,
    ], 200);
});

Route::get('cmnty/leaderboard/{t_community_id}', [CommunityController::class, 'leaderboard']);
Route::post('cmnty/join-community', [CommunityController::class, 'join_community']);
Route::apiResource('cmnty', CommunityController::class)->only(['index', 'show', 'store', 'update', 'destroy']);;
Route::post('cmnty/bulk-destroy', [CommunityController::class, 'bulk_destroy']);
Route::get('user', [CommunityController::class, 'community_user']);
Route::get('see-all/{community_id}/{model}', [CommunityController::class, 'see_all']);
Route::get('all-community', [CommunityController::class, 'all_community']);
Route::get('cmnty/members-community/{id}', [CommunityController::class, 'members_community']);

Route::prefix('manage')->group(function () {
    Route::get('', [CommunityController::class, 'index_manage']);
    Route::post('store', [CommunityController::class, 'store_manage']);
    Route::get('show/{id}', [CommunityController::class, 'show_manage']);
    Route::patch('update/{id}', [CommunityController::class, 'update_manage']);
    Route::delete('delete/{id}', [CommunityController::class, 'destroy_manage']);
});

Route::get('event/leaderboard/{t_event_id}', [EventCommonityController::class, 'leaderboard']);
Route::post('event/join-event', [EventCommonityController::class, 'join_event']);
Route::get('event/event-date', [EventCommonityController::class, 'event_date']);
Route::post('event/store-upload-payment/{t_event_id}', [EventCommonityController::class, 'store_proof_payment']);
Route::patch('event/update-upload-payment/{t_event_id}', [EventCommonityController::class, 'update_proof_payment']);
Route::apiResource('event', EventCommonityController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
Route::post('event/bulk-destroy', [EventCommonityController::class, 'bulk_destroy']);

Route::apiResource('posting', PostingCommonityController::class)->only(['index', 'show', 'store', 'update', 'destroy']);;
Route::post('posting/bulk-destroy', [PostingCommonityController::class, 'bulk_destroy']);

Route::apiResource('album', AlbumCommonityController::class)->only(['index', 'show', 'store', 'update', 'destroy']);;
Route::post('album/bulk-destroy', [AlbumCommonityController::class, 'bulk_destroy']);

Route::apiResource('photo', PhotoCommonityController::class)->only(['index', 'show', 'store', 'update', 'destroy']);;
Route::post('photo/bulk-destroy', [PhotoCommonityController::class, 'bulk_destroy']);

Route::apiResource('sponsor', SponsorCommonityController::class)->only(['index', 'show', 'store', 'update', 'destroy']);;
Route::post('sponsor/bulk-destroy', [SponsorCommonityController::class, 'bulk_destroy']);

Route::get('check_community', [EventCommonityController::class, 'check_community']);
