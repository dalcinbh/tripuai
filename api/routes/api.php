<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TravelRequestController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
    });
});

Route::middleware('auth:api')->group(function () {

    Route::get('travel-requests', [TravelRequestController::class, 'viewTravelRequests'])->name('travel-requests.index');

    Route::prefix('travel-requests/{travelRequest}')->group(function () {
        Route::get('show', [TravelRequestController::class, 'showTravelRequest'])->name('travel-requests.show');
        Route::patch('approve', [TravelRequestController::class, 'approveTravelRequest'])->name('travel-requests.approve');
        Route::patch('cancel', [TravelRequestController::class, 'cancelTravelRequest'])->name('travel-requests.cancel');
    });
});
