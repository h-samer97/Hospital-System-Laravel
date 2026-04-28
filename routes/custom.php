<?php

use App\Http\Controllers\Auth\AdminController;
use App\Http\Controllers\dashboard\DoctorController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\SingleServicesController;
use Illuminate\Support\Facades\Route;

// Admin Routes
Route::get('/dashboard/admin', function () {
    return view('dashboard.admin.dashboard');
})->middleware(['auth:admin'])->name('dashboard.admin');

Route::get('/login/admin', function () {
    return redirect()->route('login');
});

Route::post('/login/admin', [AdminController::class, 'login'])->name('login.admin');

Route::post('/logout/admin', [AdminController::class, 'logout'])->name('logout.admin');

Route::middleware(['auth:admin'])->group(function () {

    // Sections Resource
    Route::resource('sections', SectionController::class);

    // Doctors Resource
    Route::resource('doctors', DoctorController::class);

    // update doctor password
    Route::post('update_password', [DoctorController::class, 'update_password'])->name('update_password');
    Route::post('update_status', [DoctorController::class, 'update_status'])->name('update_status');

    // Single Services

    Route::resource('single_services', SingleServicesController::class)->names('SingleService');

});
