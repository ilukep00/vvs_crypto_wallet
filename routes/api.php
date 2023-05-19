<?php

use App\Infrastructure\Controllers\CreateWalletController;
use App\Infrastructure\Controllers\GetUserController;
use App\Infrastructure\Controllers\GetWalletCoinsController;
use App\Infrastructure\Controllers\GetWalletBalanceController;
use App\Infrastructure\Controllers\IsEarlyAdopterUserController;
use App\Infrastructure\Controllers\GetStatusController;
use App\Infrastructure\Controllers\SellCoinController;
use App\Infrastructure\Controllers\BuyCoinController;
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

Route::get('/status', GetStatusController::class);
Route::post('/coin/sell', SellCoinController::class);
Route::post('/coin/buy', BuyCoinController::class);
Route::post('/wallet/open', CreateWalletController::class);
Route::get('/wallet/{wallet_id}/', GetWalletCoinsController::class);
Route::get("/wallet/{wallet_id}/balance", GetWalletBalanceController::class);
