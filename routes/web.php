<?php

use App\Http\Controllers\Accounting\InventoryItemController;
use App\Http\Controllers\Accounting\InventoryStockController;
use App\Http\Controllers\Accounting\PettyCashFundController;
use App\Http\Controllers\Accounting\PettyCashTransactionController;
use App\Http\Controllers\AccountsPayableController;
use App\Http\Controllers\AccountsReceivableController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\GeneralLedgerController;
use App\Http\Controllers\InventoryStockIssueController;
use App\Http\Controllers\InventoryStockMovementController;
use App\Http\Controllers\LaboratoryController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PatientTrackingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\SuperAdminModuleController;
use App\Http\Controllers\SupplierInvoiceController;
use App\Http\Controllers\SupplierPaymentController;
use App\Http\Controllers\TestRequestController;
use App\Http\Controllers\TestRequestItemController;
use App\Http\Controllers\TestTypeController;
use App\Http\Controllers\TrialBalanceController;
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

    // Branches
    Route::resource('branches', BranchController::class);

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




Route::middleware(['auth', 'super_admin'])
    ->prefix('super-admin')
    ->name('super-admin.')
    ->group(function () {

        Route::get('/', [SuperAdminController::class, 'index'])
            ->name('dashboard');

        Route::get('/laboratories/{laboratory}/modules', [SuperAdminModuleController::class, 'edit'])
            ->name('modules.edit');

        Route::put('/laboratories/{laboratory}/modules', [SuperAdminModuleController::class, 'update'])
            ->name('modules.update');

    });

    Route::get('/accounting/general-ledger', [GeneralLedgerController::class, 'index'])
    ->name('accounting.general-ledger');
    Route::get('/accounting/accounts-receivable', [AccountsReceivableController::class, 'index'])
    ->name('accounting.accounts-receivable');
    Route::get('/accounting/accounts-receivable/{testRequest}', [AccountsReceivableController::class, 'show'])
    ->name('accounting.accounts-receivable.show');
    Route::get('/accounting/accounts-payable', [AccountsPayableController::class, 'index'])
    ->name('accounting.accounts-payable');

    Route::get('/accounting/accounts-payable/create', [SupplierInvoiceController::class, 'create'])
    ->name('accounting.accounts-payable.create');

Route::post('/accounting/accounts-payable', [SupplierInvoiceController::class, 'store'])
    ->name('accounting.accounts-payable.store');
Route::get('/accounting/accounts-payable/{supplierInvoice}', [SupplierInvoiceController::class, 'show'])
    ->name('accounting.accounts-payable.show');
    Route::get('/accounting/accounts-payable/{supplierInvoice}/payment', [SupplierPaymentController::class, 'create'])
    ->name('accounting.accounts-payable.payment.create');
    Route::post('/accounting/accounts-payable/{supplierInvoice}/payment', [SupplierPaymentController::class, 'store'])
    ->name('accounting.accounts-payable.payment.store');
   

Route::get('/accounting/trial-balance', [TrialBalanceController::class, 'index'])
    ->name('accounting.trial-balance');



Route::get('/accounting/expenses', [ExpenseController::class, 'index'])
    ->name('accounting.expenses');

Route::get('/accounting/expenses/create', [ExpenseController::class, 'create'])
    ->name('accounting.expenses.create');

Route::post('/accounting/expenses', [ExpenseController::class, 'store'])
    ->name('accounting.expenses.store');
Route::prefix('accounting/petty-cash')
    ->name('accounting.petty-cash.')
    ->group(function () {
        Route::get('/funds', [PettyCashFundController::class, 'index'])
            ->name('funds.index');

        Route::get('/funds/create', [PettyCashFundController::class, 'create'])
            ->name('funds.create');

        Route::post('/funds', [PettyCashFundController::class, 'store'])
            ->name('funds.store');
            Route::get('/funds/{pettyCashFund}/transactions', [PettyCashTransactionController::class, 'index'])
    ->name('transactions.index');

Route::get('/funds/{pettyCashFund}/transactions/create', [PettyCashTransactionController::class, 'create'])
    ->name('transactions.create');

Route::post('/funds/{pettyCashFund}/transactions', [PettyCashTransactionController::class, 'store'])
    ->name('transactions.store');
    });

Route::prefix('accounting/inventory')
    ->name('accounting.inventory.')
    ->group(function () {

        Route::get('/items', [InventoryItemController::class, 'index'])
            ->name('items.index');

        Route::get('/items/create', [InventoryItemController::class, 'create'])
            ->name('items.create');

        Route::post('/items', [InventoryItemController::class, 'store'])
            ->name('items.store');
            
          Route::get('/stocks', [InventoryStockController::class, 'index'])
            ->name('stocks.index');

        Route::get('/stocks/create', [InventoryStockController::class, 'create'])
            ->name('stocks.create');

        Route::post('/stocks', [InventoryStockController::class, 'store'])
            ->name('stocks.store');
    });   
  Route::get('/accounting/inventory/stock-issues/create', [InventoryStockIssueController::class, 'create'])
    ->name('accounting.inventory.stock-issues.create');

Route::post('/accounting/inventory/stock-issues', [InventoryStockIssueController::class, 'store'])
    ->name('accounting.inventory.stock-issues.store');   
    
Route::get('/accounting/inventory/stock-movements', [InventoryStockMovementController::class, 'index'])
    ->name('accounting.inventory.stock-movements.index');    



    
require __DIR__.'/auth.php';
