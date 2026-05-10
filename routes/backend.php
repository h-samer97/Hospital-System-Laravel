<?php

use App\Http\Controllers\Auth\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Admin\AdminDashboardController;


Route::middleware("guest")->group(function () {
    Route::get('/', function () {
        return Inertia::render('Auth/MultiLogin/MultiLoginPage');
        });
        
        // ===== POST routes لكل دور =====
Route::post('/login/user',                 [LoginController::class, 'loginUser'])          ->name('login.user');
Route::post('/login/admin',                [LoginController::class, 'loginAdmin'])         ->name('login.admin');
Route::post('/login/doctor',               [LoginController::class, 'loginDoctor'])        ->name('login.doctor');
Route::post('/login/ray',                  [LoginController::class, 'loginRayEmployee'])   ->name('login.ray');
Route::post('/login/pharmacy',             [LoginController::class, 'loginPharmacyEmployee'])->name('login.pharmacy');
Route::post('/login/lab',                  [LoginController::class, 'loginLabEmployee'])   ->name('login.lab');

    Route::get('/login/{role}', function ($role) {
        if (!in_array($role, ['user', 'admins'])) {
            abort(404);
            }
            return Inertia::render('Auth/MultiLogin/LoginPage', [
                'role' => $role,
            ]);
    })->name('login');
});



Route::middleware("auth:web")->group(function () {
    Route::get("dashboard/user", [DashboardController::class, "index"])->name("dashboard.user");
});

Route::middleware("auth:admins")->group(function () {
    Route::get('dashboard/admin',  [AdminDashboardController::class, 'index'])->name('admin.dashboard');
});