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