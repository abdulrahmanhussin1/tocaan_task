<?php

use Illuminate\Support\Facades\Route;

Route::prefix('/v1')->group(function () {

    // Authentication routes
    Route::prefix('/auth')->middleware('throttle:auth')->group(function () {
        require base_path('routes/api/v1/auth.php');
    });

    // Core routes
    require base_path('routes/api/v1/api.php');
});
