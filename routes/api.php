<?php

use App\Http\Controllers\AuthController;
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
});