<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaboratoryController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PatientTrackingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\TestRequestController;
use App\Http\Controllers\TestRequestItemController;
use App\Http\Controllers\TestTypeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/track-result', [PatientTrackingController::class, 'index'])
    ->name('patient.track');

Route::get('/track-result/search', [PatientTrackingController::class, 'search'])
    ->name('patient.track.search');

Route::get('/track-result/{trackingCode}/result/{result}/download',
    [PatientTrackingController::class, 'download'])
    ->name('patient.result.download');

 Route::get(
    '/track-result/{trackingCode}/result/{result}/view',
    [PatientTrackingController::class, 'view']
)->name('patient.result.view');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');





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

// Results

Route::prefix('results')->name('results.')->group(function () {

// Results listing
Route::get(
    '/',
    [ResultController::class, 'index']
)->name('index');

    // Upload result
    Route::get(
        'test-request-items/{testRequestItem}/create',
        [ResultController::class, 'create']
    )->name('create');

    Route::post(
        'test-request-items/{testRequestItem}',
        [ResultController::class, 'store']
    )->name('store');

    // Download result
    Route::get(
        '{result}/download',
        [ResultController::class, 'download']
    )->name('download');

    // Verify result
    Route::patch(
        '{result}/verify',
        [ResultController::class, 'verify']
    )->name('verify');

    // Replace result
    Route::get(
        '{result}/edit',
        [ResultController::class, 'edit']
    )->name('edit');

    Route::put(
        '{result}',
        [ResultController::class, 'update']
    )->name('update');
});


Route::middleware(['auth'])
    ->prefix('payments')
    ->name('payments.')
    ->group(function () {

        Route::get('/', [PaymentController::class, 'index'])
            ->name('index');

        Route::get('/create/{testRequest}', [PaymentController::class, 'create'])
            ->name('create');

        Route::post('/{testRequest}', [PaymentController::class, 'store'])
            ->name('store');

        Route::get('/{payment}', [PaymentController::class, 'show'])
            ->name('show');
    });

    Route::get('/reports', [ReportController::class, 'index'])
    ->name('reports.index');

    
require __DIR__.'/auth.php';
