<?php

use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::group(['as' => 'api.'], function () {
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('subscription', [SubscriptionController::class, 'store'])->name('subscription.store');
        Route::delete('subscription', [SubscriptionController::class, 'destroy'])->name('subscription.destroy');
    });
});