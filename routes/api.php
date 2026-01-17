<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\FakeSafraTestes;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\NotificationControllerPM;
use App\Http\Controllers\NotificationSafraController;
use App\Http\Controllers\TestesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::post('cart/reduce', [CartController::class, 'cart_reduce'])->name('cart.reduce');
// Route::post('cart/plus', [CartController::class, 'cart_plus'])->name('cart.plus');

// Route::get('session', [CheckoutController::class, 'generate_session'])->name('session_ger');

Route::post('notification', [NotificationController::class, 'index'])->name('webhook');
Route::post('notificationPM', [NotificationControllerPM::class, 'index'])->name('webhook.pagarme');


Route::post('notification_safra', [NotificationSafraController::class, 'index'])->name('webhook_safra');


// Safrapay desenvolvimento testes locais
// Route::post('credit', [FakeSafraTestes::class, 'creditPay'])->name('safra.credit');
// Route::post('token', [FakeSafraTestes::class, 'tokenPay'])->name('safra.token');
// Route::post('pix', [FakeSafraTestes::class, 'pixPay'])->name('safra.pix');
