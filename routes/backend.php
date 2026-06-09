<?php

use App\Http\Controllers\Auth\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\PatientsController;
use App\Http\Controllers\InsurancesController;
use App\Http\Controllers\AmbulancesController;
use App\Http\Controllers\SingleInvoicesController;

// الصفحة الرئيسية
Route::get('/', fn() => inertia('Welcome'))->name('home');

// Global Page type OF Guest :)
Route::middleware('guest')->group(function () {
    Route::get('/login', fn() => inertia('Auth/Login'))->name('login');
    Route::get('/register', fn() => inertia('Auth/Register'))->name('register');

    Route::get('/', function () {
        return Inertia::render('Auth/MultiLogin/MultiLoginPage');
    });

    // ===== POST routes لكل دور =====
    Route::post('/login/user', [LoginController::class, 'loginUser'])->name('login.user');
    Route::post('/login/admin', [LoginController::class, 'loginAdmin'])->name('login.admin');
    Route::post('/login/doctor', [LoginController::class, 'loginDoctor'])->name('login.doctor');
    Route::post('/login/ray', [LoginController::class, 'loginRayEmployee'])->name('login.ray');
    Route::post('/login/pharmacy', [LoginController::class, 'loginPharmacyEmployee'])->name('login.pharmacy');
    Route::post('/login/lab', [LoginController::class, 'loginLabEmployee'])->name('login.lab');

    Route::get('/login/{role}', function ($role) {
        if (!in_array($role, ['user', 'admins'])) {
            abort(404);
        }
        return Inertia::render('Auth/MultiLogin/LoginPage', [
            'role' => $role,
        ]);
    })->name('login');
});

// Routes للمستخدمين العاديين
Route::middleware("auth:web")->group(function () {
    Route::get("dashboard/user", [DashboardController::class, "index"])->name("dashboard.user");
});

// Routes للمسؤولين
Route::middleware("auth:admins")->group(function () {
    Route::get('dashboard/admin', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('sections', SectionController::class)
        ->only(['index', 'store', 'update', 'destroy', 'show']);

    # Doctors Routes
    Route::resource('doctors', DoctorController::class)
        ->only(['index', 'store', 'update', 'destroy', 'edit', 'create']);

    Route::delete('doctors/bulk-destroy', [DoctorController::class, 'destroyBulk'])
        ->name('doctors.destroyBulk');

    Route::patch('doctors/{doctor}/password', [DoctorController::class, 'updatePassword'])
        ->name('Doctors.updatePassword');

    Route::patch('doctors/{doctor}/status', [DoctorController::class, 'updateStatus'])
        ->name('Doctors.updateStatus');

    Route::resource('services', ServiceController::class)
        ->only(['index', 'store', 'update', 'destroy']);

    Route::resource('groups', GroupController::class)
        ->only(['index', 'store', 'destroy']);

    Route::resource('insurances', InsurancesController::class)
        ->only(['index', 'store', 'update', 'destroy']);

    Route::resource('patients', PatientsController::class)
        ->only(['index', 'store', 'update', 'destroy', 'show']);

    Route::resource('ambulances', AmbulancesController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

    Route::resource('single_invoices', SingleInvoicesController::class)
        ->only(['index', 'store', 'update', 'destroy']);
});
