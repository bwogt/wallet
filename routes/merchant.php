<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.v1.')->group(function () {
    Route::controller(AuthController::class)->prefix('auth')->name('auth.')->group(function () {
        Route::prefix('merchant')->name('merchant.')->group(function () {
            Route::post('/register', 'registerMerchant')->name('register');
        });
    });
});
