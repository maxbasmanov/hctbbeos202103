<?php

use Illuminate\Http\Request;
use App\Http\Controllers\API\EOS\v2\WalletController;
use App\Http\Controllers\API\EOS\v2\TransactionController;
use App\Http\Controllers\API\EOS\v2\ReferralController;
use App\Http\Controllers\API\EOS\v2\StatController;

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

Route::group(array('prefix' => 'eos/v2'), function() {
	Route::post('wallet/store', [WalletController::class, 'store']);
	Route::post('wallet/update', [WalletController::class, 'update']);
	Route::post('wallet/balance', [WalletController::class, 'balance']);

	Route::post('transactions', [TransactionController::class, 'index']);
	Route::post('transactions/store', [TransactionController::class, 'store']);

	Route::post('referrals/store', [ReferralController::class, 'store']);

	Route::post('statistics', [StatController::class, 'index']);
});
