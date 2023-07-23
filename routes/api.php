<?php

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClientApiController;
use App\Http\Controllers\Frontend\AllPageController;
use App\Http\Controllers\Api\ClientPaymentApiController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/client', [ClientApiController::class, 'index'])->name('client');
Route::get('client/payment/list', [ClientPaymentApiController::class, 'index'])->name('client.payment_list');
Route::post('client/payment/store', [ClientPaymentApiController::class, 'store'])->name('client.payment_store');
Route::get('client/package-list', [ClientPaymentApiController::class, 'packageList'])->name('client.package_list');

Route::post('payment_post', [AllPageController::class, 'payment_post'])->name('payment_post');
