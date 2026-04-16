<?php

use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\dashboard\AdminController;
use Illuminate\Support\Facades\Route;


Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]
    ], function(){

    Route::get('/dashboard/user', function () {
        return view('dashboard.user.dashboard');
    })->middleware(['auth'])->name('dashboard.user');

    Route::get('/dashboard/admin', function () {
        return view('dashboard.admin.dashboard');
    })->middleware(['auth:admin'])->name('dashboard.admin');

    Route::get('/login/admin', function () {
        return redirect()->route('login');
    });

    Route::post('/login/admin', [AdminController::class, 'login'])->name('login.admin');

    // require __DIR__.'/auth.php';

    Route::post('/logout/admin', [AdminController::class, 'logout'])->name('logout.admin');

});