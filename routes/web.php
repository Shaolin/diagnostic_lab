<?php

use App\Http\Controllers\LaboratoryController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TestRequestController;
use App\Http\Controllers\TestRequestItemController;
use App\Http\Controllers\TestTypeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Laboratories
    Route::resource('laboratories', LaboratoryController::class);

    // Users (Admin only)
    Route::middleware('admin')->group(function () {
        Route::resource('users', UserController::class);
    });

    // Patients
    Route::resource('patients', PatientController::class)
        ->except('destroy');

    Route::patch(
        'patients/{patient}/toggle-status',
        [PatientController::class, 'toggleStatus']
    )->name('patients.toggle-status');

 Route::resource('test-types', TestTypeController::class)
    ->except(['destroy']);

// Test types

    Route::patch(
    'test-types/{test_type}/activate',
    [TestTypeController::class, 'activate']
)->name('test-types.activate');

Route::patch(
    'test-types/{test_type}/deactivate',
    [TestTypeController::class, 'deactivate']
)->name('test-types.deactivate');

});

// Test Requests

 Route::resource('test-requests', TestRequestController::class);


Route::prefix('test-request-items')->name('test-request-items.')->group(function () {

    Route::patch('{testRequestItem}/collect-sample', [TestRequestItemController::class, 'collectSample'])
        ->name('collect-sample');

    Route::patch('{testRequestItem}/start', [TestRequestItemController::class, 'start'])
        ->name('start');

    Route::patch('{testRequestItem}/complete', [TestRequestItemController::class, 'complete'])
        ->name('complete');

    Route::patch('{testRequestItem}/result-ready', [TestRequestItemController::class, 'markResultReady'])
        ->name('result-ready');

    Route::patch('{testRequestItem}/result-sent', [TestRequestItemController::class, 'markResultSent'])
        ->name('result-sent');
});
require __DIR__.'/auth.php';
