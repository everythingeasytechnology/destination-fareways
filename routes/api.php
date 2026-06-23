<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FlightRouteController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
});

Route::get('/airports', [FlightRouteController::class, 'airports']);

// Protected routes (require Bearer token)
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me',      [AuthController::class, 'me']);
    });

    Route::post('/flight-routes', [FlightRouteController::class, 'store']);
});
