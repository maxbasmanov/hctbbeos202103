<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\LogController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get(
    '/',
    [TransactionController::class, 'index']
)->name('transactions.index');

Route::get(
    '/search',
    [TransactionController::class, 'search']
)->name('transactions.search');

Route::get(
    '/transaction/{transaction}',
    [TransactionController::class, 'view']
)->name('transactions.view');

Route::get(
    '/logs',
    [LogController::class, 'index']
)->name('logs.index');

Route::get(
    '/logs/{log}',
    [LogController::class, 'view']
)->name('logs.view');
