<?php

use App\Http\Controllers\API\EventController;
use App\Http\Controllers\Auth\App\AuthController;
use App\Http\Controllers\Auth\App\RegisterAppUserController;
use App\Http\Controllers\CallbackController;
use App\Http\Controllers\DeleteImageController;
use App\Http\Controllers\Event\PaymentController;
use App\Http\Controllers\WhatsApp\WhatsAppController;
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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('guest')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/verify_otp', [AuthController::class, 'verifyOTP']);
    Route::post('/verify_otp1', [AuthController::class, 'verifyOTP1']);


    Route::post('/resend_OTP/{id}', [RegisterAppUserController::class, 'resendOTP']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/current_user', [AuthController::class, 'currentUser']);
    Route::post('/verify_pledge_by_code', [EventController::class, 'verifyPledgeByCode']);
    Route::post('/verifySinglePledge', [EventController::class, 'verifySinglePledge']);
    Route::post('/verifyDoublePledge', [EventController::class, 'verifyDoublePledge']);
    Route::post('/verify_code', [EventController::class, 'verifyCode']);
    Route::post('/verify_code_by_normal', [EventController::class, 'verifyCodeByNormalUser']);
});

Route::get('shorten-url', [EventController::class, 'shortenUrl']);
Route::get('/order/is_paid/check/{id}', [PaymentController::class, 'isPaidTest']);

Route::post('/sendMessageWhatsApp', [PaymentController::class, 'sendMessage']);

// Delete image controller routes
Route::get('/getAllWhatsappImages', [DeleteImageController::class, 'getAllWhatsappImages']);
Route::get('/deleteAllWhatsappImages', [DeleteImageController::class, 'deleteAllWhatsappImages']);

Route::get('/getAllWelcomeNotes', [DeleteImageController::class, 'getAllWelcomeNotes']);
Route::get('/deleteWelcomeNotesExceptOne', [DeleteImageController::class, 'deleteWelcomeNotesExceptOne']);

Route::post('/callback', [CallbackController::class, 'receive']);





require_once __DIR__ . '/api/event.php';
