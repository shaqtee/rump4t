<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DimensionsController;
use Illuminate\Support\Facades\Route;
use Modules\News\App\Http\Controllers\NewsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::get('/', function (){
    $data = [];

    return response()->json([
        'code' => 200,
        'Massage' => 'Welcome To DGOLF API Powered By Aksi!',
        'data' => $data,
    ], 200);
});

Route::apiResource('dimensions', DimensionsController::class)->only('index', 'store');
Route::middleware("auth:api")->post('delete-account', [NewsController::class, 'deleteSelfAccount']);

Route::prefix('sign-up')->group(function() {
    Route::post('get-user', [AuthController::class, 'get_user2']);
    Route::post('registrasi', [AuthController::class, 'registrasi']); // new sync
    Route::post('verifikasi-phone-user', [AuthController::class, 'verifikasi_phone_user']);
    Route::post('continue-registration', [AuthController::class, 'continue_registration']);
    Route::post('verifikasi-email-user', [AuthController::class, 'verifikasi_email_user']);
    Route::post('save-profile', [AuthController::class, 'save_profile']);
    Route::post('resend-otp', [AuthController::class, 'resend_otp']);
});

Route::prefix('sign-in')->group(function() {
    Route::post('get-user', [AuthController::class, 'get_user']); // new sync
    Route::post('login-to-send-otp', [AuthController::class, 'login_to_send_otp']);
    Route::post('verify-login', [AuthController::class, 'verify_login'])->name('login');
    Route::post('login-cepat', [AuthController::class, 'loginCepat'])->name('login');
    Route::post('resend-otp-login', [AuthController::class, 'resend_otp_login']);
});


Route::get('user-reset-request', [AuthController::class, 'user_reset_request']);
Route::middleware("auth:api")->get('accept-eula', [AuthController::class, 'accept_eula']);
Route::get('list-region', [AuthController::class, 'list_region']); // new
Route::get('list-area/{region_id}', [AuthController::class, 'list_area']); // new

Route::prefix('auth')->middleware('auth:api')->group(function () {
    Route::get('check-login', function(){
        echo 'logged_in';
    });
    Route::get('get-auth', [AuthController::class, 'getAuth']);
    Route::post('update-profile/{id}', [AuthController::class, 'update_profile']); // new sync
    Route::patch('update-email-phone', [AuthController::class, 'update_email_phone']);
    Route::post('verify-update-email-phone', [AuthController::class, 'verify_update_email_phone']);
    Route::post('update-cross', [AuthController::class, 'update_cross']);
    Route::post('verify-update-cross', [AuthController::class, 'verify_update_cross']);
    Route::post('verify-new-update-email-phone', [AuthController::class, 'verify_new_update_email_phone']);
    Route::post('allow-notification', [AuthController::class, 'allow_notification']);// set token fcm
    Route::post('push-notification', [AuthController::class, 'push_notification']);
    Route::get('delete-account', [AuthController::class, 'delete_account']);
    Route::post('aggree-eula', [AuthController::class, 'aggree_with_eula']);

    // Route::get('list-region', [AuthController::class, 'list_region']); // new
    Route::get('list-city', [AuthController::class, 'list_city']); // new
    Route::post('logout', [AuthController::class, 'logout']); // new

    Route::get('total-user', [AuthController::class, 'total_user']);
    Route::get('total-member', [AuthController::class, 'total_member']);
    Route::get('total-member-khusus', [AuthController::class, 'total_member_khusus']);

    Route::get('search-by-name/{name}', [AuthController::class, 'search_by_name']);
    Route::get('search-by-region/{region}', [AuthController::class, 'search_by_region']);
    Route::get('selected-region/{id}', [AuthController::class, 'selected_region']);
    Route::get('selected-area/{id}', [AuthController::class, 'selected_area']);
    Route::get('search-by-city/{name}', [AuthController::class, 'search_by_city']);
    Route::get('selected-city/{id}', [AuthController::class, 'selected_city']);

    Route::get('search-by-regency/{name}', [AuthController::class, 'search_by_regency']);
    Route::get('selected-regency/{id}', [AuthController::class, 'selected_regency']);
});