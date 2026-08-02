<?php

use App\Http\Controllers\ProfileController;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth')->group(function () {
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update']);
  Route::delete('/profile', [ProfileController::class, 'destroy']);
});

Route::get('health', function(){

  $check = [];

  try {
    DB::connection()->getPdo();
    $check['database'] = 'ok';
  } catch(Exception $error) {
    $check['database'] = 'fail => ' . $error->getMessage();
  }

  # Check Redis Cash
  try {
    Cache::store('redis')->ping();
    $check['redis'] = 'ok';
  } catch(Exception $error) {
    $check['redis'] = 'fail => ' . $error->getMessage();
  }

  # CHeck Storage Writable
  $check['storage'] = is_writable(storage_path()) ? 'ok' : 'fail';

  # Check PDF DOM
  try {
    $pdf = Pdf::loadHTML('<h1>TEST</h1>');
    $pdf->output();
    $check['pdf'] = 'ok';
  } catch(Exception $error) {
    $check['pdf'] = 'fail => ' . $error->getMessage();
  }

  # 503 Service Unavalibale
  # 200 OK
  $status = in_array('fail', $check) ? 503 : 200;

  return response()->json([
    'status' => $status === 200 ? 'healthy' : 'degraded',
    'version' => config('app.version', '1.0'),
    'checks' => $check,
    'time' => now()->toISOString()
  ], $status);

})->name('health');

require __DIR__ . '/auth.php';
require __DIR__ . '/backend.php';
