<aside class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 border-r border-slate-700 overflow-y-auto">

    <!-- Logo -->
    <div class="flex h-16 items-center border-b border-slate-700 px-6">

        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">

            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-600 text-xl font-bold text-white">
                🧪
            </div>

            <div>
                <h1 class="text-lg font-bold text-white">
                    Diagnostic Lab
                </h1>

               <p class="text-xs text-slate-400">

    @if(auth()->user()->isSuperAdmin())
        Super Admin
    @elseif(auth()->user()->isAdmin())
        Laboratory Administrator
    @else
        Staff
    @endif

</p>
            </div>

        </a>

    </div>

    
  
<!-- Navigation -->
<nav class="mt-6 space-y-2 px-4">

    {{-- Dashboard --}}
    <a href="{{ route('dashboard') }}"
       class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition
       {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
        <span>🏠</span>
        Dashboard
    </a>


    {{-- Laboratory Management --}}
    @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())

        @php
            $managementOpen = request()->routeIs('laboratories.*')
                || request()->routeIs('users.*')
                || request()->routeIs('branches.*');
        @endphp

        <div x-data="{ open: {{ $managementOpen ? 'true' : 'false' }} }">

            <button
                type="button"
                @click="open = !open"
                class="flex w-full items-center justify-between rounded-lg px-4 py-3 text-sm font-medium transition
                {{ $managementOpen ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
            >
                <span class="flex items-center gap-3">
                    <span>🏥</span>
                    Laboratory Management
                </span>

                <span class="text-xs transition-transform"
                      :class="{ 'rotate-180': open }">
                    ▼
                </span>
            </button>

            <div x-show="open" x-transition class="mt-1 space-y-1 pl-4">

                {{-- Laboratories --}}
                <a href="{{ route('laboratories.index') }}"
                   class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium transition
                   {{ request()->routeIs('laboratories.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <span>🏥</span>
                    Laboratories
                </a>

                {{-- Users --}}
                <a href="{{ route('users.index') }}"
                   class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium transition
                   {{ request()->routeIs('users.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <span>👤</span>
                    Users
                </a>

                {{-- Branches --}}
                <a href="{{ route('branches.index') }}"
                   class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium transition
                   {{ request()->routeIs('branches.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <span>🏢</span>
                    Branches
                </a>

            </div>
        </div>

    @endif


    {{-- Laboratory Operations --}}
    @php
        $operationsOpen = request()->routeIs('patients.*')
            || request()->routeIs('test-types.*')
            || request()->routeIs('test-requests.*')
            || request()->routeIs('results.*')
            || request()->routeIs('payments.*');
    @endphp

    <div x-data="{ open: {{ $operationsOpen ? 'true' : 'false' }} }">

        <button
            type="button"
            @click="open = !open"
            class="flex w-full items-center justify-between rounded-lg px-4 py-3 text-sm font-medium transition
            {{ $operationsOpen ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
        >
            <span class="flex items-center gap-3">
                <span>🧪</span>
                Laboratory Operations
            </span>

            <span class="text-xs transition-transform"
                  :class="{ 'rotate-180': open }">
                ▼
            </span>
        </button>

        <div x-show="open" x-transition class="mt-1 space-y-1 pl-4">

            {{-- Patients --}}
            <a href="{{ route('patients.index') }}"
               class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium transition
               {{ request()->routeIs('patients.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <span>🩺</span>
                Patients
            </a>

            {{-- Test Types --}}
            <a href="{{ route('test-types.index') }}"
               class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium transition
               {{ request()->routeIs('test-types.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <span>🧪</span>
                Test Types
            </a>

            {{-- Test Requests --}}
            <a href="{{ route('test-requests.index') }}"
               class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium transition
               {{ request()->routeIs('test-requests.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <span>🧾</span>
                Test Requests
            </a>

            {{-- Results --}}
            <a href="{{ route('results.index') }}"
               class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium transition
               {{ request()->routeIs('results.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <span>📄</span>
                Results
            </a>

            {{-- Payments --}}
            <a href="{{ route('payments.index') }}"
               class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium transition
               {{ request()->routeIs('payments.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <span>💳</span>
                Payments
            </a>

        </div>
    </div>


    {{-- Accounting --}}
    @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())

        @php
            $accountingOpen = request()->routeIs('accounting.*');
        @endphp

        <div x-data="{ open: {{ $accountingOpen ? 'true' : 'false' }} }">

            <button
                type="button"
                @click="open = !open"
                class="flex w-full items-center justify-between rounded-lg px-4 py-3 text-sm font-medium transition
                {{ $accountingOpen ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
            >
                <span class="flex items-center gap-3">
                    <span>📊</span>
                    Accounting
                </span>

                <span class="text-xs transition-transform"
                      :class="{ 'rotate-180': open }">
                    ▼
                </span>
            </button>

            <div x-show="open" x-transition class="mt-1 space-y-1 pl-4">

                {{-- General Ledger --}}
                <a href="{{ route('accounting.general-ledger') }}"
                   class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium transition
                   {{ request()->routeIs('accounting.general-ledger') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <span>📒</span>
                    Account Ledger
                </a>

                {{-- Branch Income / Sales --}}
<a href="{{ route('accounting.branch-income.index') }}"
   class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium transition
   {{ request()->routeIs('accounting.branch-income.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

    <span>📊</span>
    Branch Income / Sales
</a>
{{-- Profit & Loss --}}
<a href="{{ route('accounting.profit-loss.index') }}"
   class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium transition
   {{ request()->routeIs('accounting.profit-loss.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

    <span>📈</span>
    Profit & Loss
</a>

{{-- Balance Sheet --}}
<a href="{{ route('accounting.balance-sheet.index') }}"
   class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium transition
   {{ request()->routeIs('accounting.balance-sheet.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

    <span>📋</span>
    Balance Sheet
</a>

{{-- Cash Flow --}}
<a href="{{ route('accounting.cash-flow') }}"
   class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium transition
   {{ request()->routeIs('accounting.cash-flow') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

    <span>💵</span>
    Cash Flow
</a>

{{-- Monthly Financial Reports --}}
<a href="{{ route('accounting.monthly-financial-report') }}"
   class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium transition
   {{ request()->routeIs('accounting.monthly-financial-report') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

    <span>📊</span>
    Monthly Financial Reports
</a>
{{-- Branch Reports --}}
<a href="{{ route('accounting.branch-reports') }}"
   class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium transition
   {{ request()->routeIs('accounting.branch-reports') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

    <span>🏢</span>
    Branch Reports
</a>

                {{-- Petty Cash --}}
                <a href="{{ route('accounting.petty-cash.funds.index') }}"
                   class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium transition
                   {{ request()->routeIs('accounting.petty-cash.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <span>💵</span>
                    Petty Cash
                </a>

                {{-- Inventory --}}
<a href="{{ route('accounting.inventory.stocks.index') }}"
    class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium transition
    {{ request()->routeIs('accounting.inventory.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
    <span>📦</span>
    Inventory
</a>

{{-- Issue Stock --}}
<a href="{{ route('accounting.inventory.stock-issues.create') }}"
    class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium transition
    {{ request()->routeIs('accounting.inventory.stock-issues.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
    <span>📤</span>
    Issue Stock
</a>
{{-- Stock Movement --}}
<a href="{{ route('accounting.inventory.stock-movements.index') }}"
    class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium transition
    {{ request()->routeIs('accounting.inventory.stock-movements.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
    <span>📋</span>
    Stock Movement
</a>

<a href="{{ route('accounting.fixed-assets.index') }}"
    class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium transition
    {{ request()->routeIs('accounting.fixed-assets.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
    <span>🏢</span>
    Fixed Assets
</a>

{{-- Bank Accounts --}}
<a href="{{ route('accounting.bank-accounts.index') }}"
   class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium transition
   {{ request()->routeIs('accounting.bank-accounts.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

    <span>🏦</span>
    Bank Accounts
</a>
{{-- Bank Reconciliation --}}
<a href="{{ route('accounting.bank-reconciliation.index') }}"
   class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium transition
   {{ request()->routeIs('accounting.bank-reconciliation.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

    <span>🔄</span>
    Bank Reconciliation
</a>
                {{-- Accounts Receivable --}}
                <a href="{{ route('accounting.accounts-receivable') }}"
                   class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium transition
                   {{ request()->routeIs('accounting.accounts-receivable*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <span>💰</span>
                    Accounts Receivable
                </a>

                {{-- Accounts Payable --}}
                <a href="{{ route('accounting.accounts-payable') }}"
                   class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium transition
                   {{ request()->routeIs('accounting.accounts-payable*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <span>📋</span>
                    Accounts Payable
                </a>

                {{-- Operating Expenses --}}
                <a href="{{ route('accounting.expenses') }}"
                   class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium transition
                   {{ request()->routeIs('accounting.expenses*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <span>💸</span>
                    Operating Expenses
                </a>

                {{-- Trial Balance --}}
                <a href="{{ route('accounting.trial-balance') }}"
                   class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium transition
                   {{ request()->routeIs('accounting.trial-balance*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <span>📊</span>
                    Trial Balance
                </a>

            </div>
        </div>

    @endif


    {{-- Reports --}}
    <a href="{{ route('reports.index') }}"
       class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition
       {{ request()->routeIs('reports.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
        <span>📊</span>
        Reports
    </a>


    {{-- Settings --}}
    <div class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm text-slate-500">
        <span>⚙️</span>
        Settings
    </div>

</nav>





</aside>


<?php

use App\Http\Controllers\Accounting\AuditTrailController;
use App\Http\Controllers\Accounting\BranchReportController;
use App\Http\Controllers\Accounting\CashFlowController;
use App\Http\Controllers\Accounting\InventoryItemController;
use App\Http\Controllers\Accounting\InventoryStockController;
use App\Http\Controllers\Accounting\MonthlyFinancialReportController;
use App\Http\Controllers\Accounting\PettyCashFundController;
use App\Http\Controllers\Accounting\PettyCashTransactionController;
use App\Http\Controllers\AccountsPayableController;
use App\Http\Controllers\AccountsReceivableController;
use App\Http\Controllers\BalanceSheetController;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\BankReconciliationController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\BranchIncomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\FixedAssetController;
use App\Http\Controllers\FixedAssetDepreciationController;
use App\Http\Controllers\GeneralLedgerController;
use App\Http\Controllers\InventoryStockIssueController;
use App\Http\Controllers\InventoryStockMovementController;
use App\Http\Controllers\LaboratoryController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PatientTrackingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfitLossController;
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

    Route::get('/accounting/fixed-assets', [FixedAssetController::class, 'index'])
    ->name('accounting.fixed-assets.index');
    Route::get('/accounting/fixed-assets/create', [FixedAssetController::class, 'create'])
    ->name('accounting.fixed-assets.create');

Route::post('/accounting/fixed-assets', [FixedAssetController::class, 'store'])
    ->name('accounting.fixed-assets.store');
Route::get('/accounting/fixed-assets/depreciation/create', [FixedAssetDepreciationController::class, 'create'])
    ->name('accounting.fixed-assets.depreciation.create');

Route::post('/accounting/fixed-assets/depreciation', [FixedAssetDepreciationController::class, 'store'])
    ->name('accounting.fixed-assets.depreciation.store');

Route::get('/accounting/bank-reconciliation', [BankReconciliationController::class, 'index'])
    ->name('accounting.bank-reconciliation.index');

Route::get('/accounting/bank-reconciliation/create', [BankReconciliationController::class, 'create'])
    ->name('accounting.bank-reconciliation.create');

Route::post('/accounting/bank-reconciliation', [BankReconciliationController::class, 'store'])
    ->name('accounting.bank-reconciliation.store');

Route::get('/accounting/bank-reconciliation/{bankReconciliation}', [BankReconciliationController::class, 'show'])
    ->name('accounting.bank-reconciliation.show');



Route::get('/accounting/bank-accounts', [BankAccountController::class, 'index'])
    ->name('accounting.bank-accounts.index');

Route::get('/accounting/bank-accounts/create', [BankAccountController::class, 'create'])
    ->name('accounting.bank-accounts.create');

Route::post('/accounting/bank-accounts', [BankAccountController::class, 'store'])
    ->name('accounting.bank-accounts.store');
Route::post('/accounting/bank-reconciliation/{bankReconciliation}/reconcile', [BankReconciliationController::class, 'reconcile'])
    ->name('accounting.bank-reconciliation.reconcile');

    Route::post('/accounting/bank-reconciliation/{bankReconciliation}/complete', [BankReconciliationController::class, 'complete'])
    ->name('accounting.bank-reconciliation.complete');

    Route::get('/accounting/branch-income', [BranchIncomeController::class, 'index'])
    ->name('accounting.branch-income.index');


Route::get('/accounting/profit-loss', [ProfitLossController::class, 'index'])
    ->name('accounting.profit-loss.index');


Route::get('/accounting/balance-sheet', [BalanceSheetController::class, 'index'])
    ->name('accounting.balance-sheet.index');

Route::get('/accounting/cash-flow', [CashFlowController::class, 'index'])
    ->name('accounting.cash-flow');

Route::get(
    '/accounting/monthly-financial-report',
    [MonthlyFinancialReportController::class, 'index']
)->name('accounting.monthly-financial-report');




Route::get(
    '/accounting/branch-reports',
    [BranchReportController::class, 'index']
)->name('accounting.branch-reports');



Route::get(
    '/accounting/audit-trail',
    [AuditTrailController::class, 'index']
)->name('accounting.audit-trail');
    
require __DIR__.'/auth.php';
