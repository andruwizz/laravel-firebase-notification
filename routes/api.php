<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::group(['as' => 'api.'], function () {
    Route::post('authentication', [AuthenticationController::class, 'store'])->name('authentication.store');

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::delete('authentication', [AuthenticationController::class, 'destroy'])->name('authentication.destroy');

        Route::post('subscription', [SubscriptionController::class, 'store'])->name('subscription.store');
        Route::delete('subscription', [SubscriptionController::class, 'destroy'])->name('subscription.destroy');

        Route::post('notification', [NotificationController::class, 'store'])->name('notification.store');
    });
});