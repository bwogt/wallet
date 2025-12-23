<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/consumer.php';
require __DIR__ . '/merchant.php';

Route::get('/', function () {
    return response()->json([
        'app' => config('app.name'),
        'laravel_version' => app()->version(),
    ]);
});

Route::prefix('v1')->name('api.v1.')->group(function () {
    Route::controller(AuthController::class)->prefix('auth')->name('auth.')->group(function () {
        Route::post('login', 'login')->name('login');
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::controller(AuthController::class)->prefix('auth')->name('auth.')->group(function () {
            Route::post('logout', 'logout')->name('logout');
        });

        Route::controller(TransactionController::class)->prefix('transactions')
            ->name('transactions.')->group(function () {
                Route::post('deposit', 'deposit')->name('deposit');
                Route::post('transfer', 'transfer')->name('transfer');
            });
    });
});
