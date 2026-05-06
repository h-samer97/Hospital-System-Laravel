<?php

use App\Http\Controllers\Auth\AdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\AmbulancesController;
use App\Http\Controllers\dashboard\DoctorController;
use App\Http\Controllers\InsuranceController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\SingleServicesController;
use App\Http\Controllers\PatientController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Admin Routes
Route::get('/dashboard/admin', function () {
    return Inertia::render('dashboard/AdminDashboard');
})->middleware(['auth:admin'])->name('dashboard.admin');

Route::get('/login/admin', function () {
    return Inertia::render('dashboard/forms/Login');
})->name('login.admin.view');

Route::post('/login/admin', [AuthenticatedSessionController::class, 'store'])->name('login.admin');

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
    Route::get('Add_GroupServices', function () {
    return Inertia::render('groupservices/ServiceGroupForm');
})->name('Add_GroupServices');

    Route::resource('insurance', InsuranceController::class);

    Route::resource('ambulance', AmbulancesController::class);

    Route::resource('patients', PatientController::class);

    Route::get('single_invoices', function () {
        return Inertia::render('singleInvoices/SingleInvoiceManager');
    })->name('single_invoices');

});
