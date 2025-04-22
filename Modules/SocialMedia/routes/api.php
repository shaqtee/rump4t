<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\SocialMedia\App\Http\Controllers\ElectionsController;
use Modules\SocialMedia\App\Http\Controllers\FormGroupDiscussionController;
use Modules\SocialMedia\App\Http\Controllers\InformationController;
use Modules\SocialMedia\App\Http\Controllers\SocialMediaController;

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
        "Module" => "\Social Media",
    ];

    return response()->json([
        'code' => 200,
        'Massage' => 'Welcome To DGOLF API Powered By Aksi',
        'data' => $data,
    ], 200);
});


Route::post('store-postingan', [SocialMediaController::class, 'store']);
Route::get('list-postingan', [SocialMediaController::class, 'index']);
Route::delete('destroy-postingan/{id}', [SocialMediaController::class, 'destroy_post']);
Route::post('report-postingan', [SocialMediaController::class, 'report_post']);
Route::post('posts/{id}/store-detail', [SocialMediaController::class, 'storedetail']);
Route::get('posts/{id}/show-detail', [SocialMediaController::class, 'showdetail']);
Route::post('block-user', [SocialMediaController::class, 'blockuser']);
Route::post('unblock-user', [SocialMediaController::class, 'unblockuser']);
Route::apiResource('sm', SocialMediaController::class);

// delete comment
Route::delete('comment/{id}', [SocialMediaController::class, 'deleteDetail']);

Route::apiResource('information', InformationController::class);
Route::apiResource('form-group-discussion', FormGroupDiscussionController::class);
Route::prefix('form-group-discussion')->group(function() {
    Route::get('invite/get-users', [FormGroupDiscussionController::class, 'getUsers']);
    Route::post('store-members/{id}', [FormGroupDiscussionController::class, 'storeMembers']);
    Route::post('store-message/{id}', [FormGroupDiscussionController::class, 'storeMessage']);
    Route::delete('delete-message/{id}', [FormGroupDiscussionController::class, 'deleteMessage']);
});
Route::apiResource('election', ElectionsController::class);
