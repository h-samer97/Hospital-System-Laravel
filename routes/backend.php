<?php

use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\Auth\AdminAuthController;
use Illuminate\Support\Facades\Route;

// Admin Auth
Route::prefix('admin')->name('admin.')->group(function () {

    // Guest routes
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AdminAuthController::class, 'showLogin'])
            ->name('login');
        Route::post('login', [AdminAuthController::class, 'login'])
            ->name('login.post');
    });

    // Protected routes
    Route::middleware('auth:admin')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');
        Route::post('logout', [AdminAuthController::class, 'logout'])
            ->name('logout');
    });
});




