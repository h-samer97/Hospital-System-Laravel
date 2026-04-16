<?php

use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\dashboard\AdminController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::get('/dashboard', function () {
    return view('dashboard.user.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
], function() {
    
    Route::get('/', function () {
        return view('welcome');
    });

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

    Route::post('/logout/admin', [AdminController::class, 'logout'])->name('logout.admin');
    
    require __DIR__.'/auth.php'; 
});