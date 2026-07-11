<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth')->group(function () {
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update']);
  Route::delete('/profile', [ProfileController::class, 'destroy']);
});

require __DIR__ . '/auth.php';
require __DIR__ . '/backend.php';
