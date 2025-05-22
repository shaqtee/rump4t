<?php

use Illuminate\Support\Facades\Route;
use Modules\Masters\App\Http\Controllers\CityController;
use Modules\Masters\App\Http\Controllers\GroupsController;
use Modules\Masters\App\Http\Controllers\MastersController;
use Modules\Masters\App\Http\Controllers\MasterReferencesController;
use Modules\Masters\App\Http\Controllers\MasterConfigurationController;

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
        "Module" => "\Masters",
    ];

    return response()->json([
        'code' => 200,
        'Massage' => 'Welcome To DGOLF API Powered By Aksi',
        'data' => $data,
    ], 200);
});

// Master Configuration
Route::apiResource('configuration', MasterConfigurationController::class);
Route::post('configuration/bulk-destroy',  [MasterConfigurationController::class, 'bulk_destroy']);

// Master References
Route::prefix('reference')->group(function () {
    Route::get('{parameter}/', [MasterReferencesController::class, 'index']);
    Route::post('{parameter}/store', [MasterReferencesController::class, 'store']);
    Route::get('{parameter}/show/{id}', [MasterReferencesController::class, 'show']);
    Route::patch('{parameter}/update/{id}', [MasterReferencesController::class, 'update']);
    Route::delete('{parameter}/destroy/{id}', [MasterReferencesController::class, 'destroy']);
    Route::post('{parameter}/bulk-destroy', [MasterReferencesController::class, 'bulk_destroy']);
});

// Master Groups
Route::apiResource('groups', GroupsController::class);
Route::post('groups/bulk-destroy',  [GroupsController::class, 'bulk_destroy']);

// Master City
Route::apiResource('city', CityController::class);
Route::post('city/bulk-destroy',  [CityController::class, 'bulk_destroy']);

// Master Get Data
Route::withoutMiddleware('auth:api')->group(function () {
    Route::get('all-users', [MastersController::class, 'get_user']);
    Route::get('all-faculty', [MastersController::class, 'get_faculty']);
    Route::get('all-type-score', [MastersController::class, 'get_type_score']);
    Route::get('all-period', [MastersController::class, 'get_period']);
    
    Route::get('all-city', [MastersController::class, 'get_city']);
    Route::get('all-province/{simple}', [MastersController::class, 'get_province']);
    Route::get('all-regency/{parent}', [MastersController::class, 'get_regency']);
    Route::get('all-district/{parent}', [MastersController::class, 'get_district']);
    Route::get('all-village/{simple}', [MastersController::class, 'get_village']);

    Route::get('get-regency-by-id/{id}', [MastersController::class, 'get_regencyById']);
    Route::get('get-district-by-id/{id}', [MastersController::class, 'get_districtById']);
    Route::get('get-village-by-id/{id}', [MastersController::class, 'get_villageById']);
});

Route::get('all-course', [MastersController::class, 'get_course']);
Route::get('all-tee', [MastersController::class, 'get_tee']);
Route::get('all-round-type', [MastersController::class, 'get_round_type']);
Route::get('all-max-flight', [MastersController::class, 'get_max_flight']);
Route::get('banner-slide', [MastersController::class, 'banner_slide']);