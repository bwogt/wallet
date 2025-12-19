<?php

use Illuminate\Support\Facades\Route;

require __DIR__ . '/consumer.php';

Route::get('/', function () {
    return response()->json([
        'app' => config('app.name'),
        'laravel_version' => app()->version(),
    ]);
});
