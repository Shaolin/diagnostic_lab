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
    

    @if(
    auth()->user()->isSuperAdmin()
    || auth()->user()->isAdmin()
    || auth()->user()->hasPermission('laboratories')
    || auth()->user()->hasPermission('users')
    || auth()->user()->hasPermission('branches')
)

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
@if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin() || auth()->user()->hasPermission('laboratories'))

    <a href="{{ route('laboratories.index') }}"
       class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium transition
       {{ request()->routeIs('laboratories.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
        <span>🏥</span>
        Laboratories
    </a>

@endif
                
              {{-- Users --}}
@if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin() || auth()->user()->hasPermission('users'))

    <a href="{{ route('users.index') }}"
       class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium transition
       {{ request()->routeIs('users.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
        <span>👤</span>
        Users
    </a>

@endif

                
               {{-- Branches --}}
@if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin() || auth()->user()->hasPermission('branches'))

    <a href="{{ route('branches.index') }}"
       class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium transition
       {{ request()->routeIs('branches.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
        <span>🏢</span>
        Branches
    </a>

@endif

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
              @if(auth()->user()->isAdmin() || auth()->user()->hasPermission('patients'))
            <a href="{{ route('patients.index') }}"
               class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium transition
               {{ request()->routeIs('patients.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <span>🩺</span>
                Patients
            </a>
            @endif
          
    


            {{-- Test Types --}}
        @if(auth()->user()->isAdmin() || auth()->user()->hasPermission('test_types'))
            <a href="{{ route('test-types.index') }}"
               class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium transition
               {{ request()->routeIs('test-types.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <span>🧪</span>
                Test Types
            </a>
        @endif

            

   

    {{-- existing Test Requests link --}}




            {{-- Test Requests --}}

          @if(auth()->user()->isAdmin() || auth()->user()->hasPermission('test_requests'))
            <a href="{{ route('test-requests.index') }}"
               class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium transition
               {{ request()->routeIs('test-requests.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <span>🧾</span>
                Test Requests
            </a>
        @endif


      

   



            {{-- Results --}}
          @if(auth()->user()->isAdmin() || auth()->user()->hasPermission('results'))    
            <a href="{{ route('results.index') }}"
               class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium transition
               {{ request()->routeIs('results.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <span>📄</span>
                Results
            </a>

            @endif

            {{-- Payments --}}

@if(
    auth()->user()->isAdmin()
    || auth()->user()->isAccountant()
    || auth()->user()->hasPermission('payments')
)

     <a href="{{ route('payments.index') }}"
               class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium transition
               {{ request()->routeIs('payments.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <span>💳</span>
                Payments
            </a>

@endif


        </div>
    </div>



    {{-- Accounting --}}
   @php
    $accountingEnabled = \App\Models\LaboratoryModule::where('laboratory_id', auth()->user()->laboratory_id)
        ->where('module', 'accounting')
        ->where('enabled', true)
        ->exists();
@endphp

@if(
    (auth()->user()->isAdmin() || auth()->user()->isAccountant())
    && $accountingEnabled
)
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

{{-- Inventory Items --}}
<a href="{{ route('accounting.inventory.items.index') }}"
   class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium transition
   {{ request()->routeIs('accounting.inventory.items.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
    <span>🧪</span>
    Inventory Items
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

                {{-- Suppliers --}}
<a href="{{ route('suppliers.index') }}"
   class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium transition
   {{ request()->routeIs('suppliers.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
    <span>🏢</span>
    Suppliers
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

    @if(auth()->user()->isAdmin() || auth()->user()->isAccountant())
    <a href="{{ route('reports.index') }}"
       class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition
       {{ request()->routeIs('reports.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
        <span>📊</span>
        Reports
    </a>
@endif

    {{-- Settings --}}
    <div class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm text-slate-500">
        <span>⚙️</span>
        Settings
    </div>

</nav>





</aside>


<!-- laboratory form -->


<div class="rounded-xl border border-slate-700 bg-slate-800 shadow-xl">

    <div class="p-6">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Laboratory Name --}}
            <div>
                <label for="name" class="block text-sm font-medium text-slate-300">
                    Laboratory Name <span class="text-red-500">*</span>
                </label>

                <input
                   type="text"
                   id="name"
                   name="name"
                   value="{{ old('name', $laboratory->name ?? '') }}"
                   class="mt-1 block w-full rounded-lg border border-slate-600 bg-slate-900 px-3 py-2 text-white placeholder-slate-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">

                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Subdomain --}}
            <div>
                <label for="subdomain" class="block text-sm font-medium text-slate-300">
                    Subdomain <span class="text-red-500">*</span>
                </label>

                <input
                    type="text"
                    id="subdomain"
                    name="subdomain"
                    value="{{ old('subdomain', $laboratory->subdomain ?? '') }}"
                    class="mt-1 block w-full rounded-lg border border-slate-600 bg-slate-900 text-white placeholder-slate-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">

                @error('subdomain')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Logo --}}
             <div>
                <label for="logo" class="block text-sm font-medium text-slate-300">
                    Logo
                </label>

                <input
                    type="file"
                    id="logo"
                    name="logo"
                    
                    class="mt-1 block w-full rounded-lg border border-slate-600 bg-slate-900 px-3 py-2 text-sm text-slate-300 file:mr-4 file:rounded-md file:border-0 file:bg-blue-600 file:px-4 file:py-2 file:text-white hover:file:bg-blue-700">

                @error('logo')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

                @isset($laboratory)
                    @if($laboratory->logo_url)
                        <div class="mt-3">
                            <img
                                src="{{ $laboratory->logo_url }}"
                                alt="{{ $laboratory->name }}"
                                class="h-20 w-20 rounded object-cover">
                        </div>
                    @endif
                @endisset
            </div>

            {{-- Phone --}}
            <div>
                <label for="phone" class="block text-sm font-medium text-slate-300">
                    Phone
                </label>

                <input
                    type="text"
                    id="phone"
                    name="phone"
                    value="{{ old('phone', $laboratory->phone ?? '') }}"
                    class="mt-1 block w-full rounded-lg border border-slate-600 bg-slate-900 text-white placeholder-slate-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">

                @error('phone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-medium text-slate-300">
                    Email
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email', $laboratory->email ?? '') }}"
                    class="mt-1 block w-full rounded-lg border border-slate-600 bg-slate-900 text-white placeholder-slate-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">

                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Country --}}
            <div>
                <label for="country" class="block text-sm font-medium text-slate-300">
                    Country
                </label>

                <input
                    type="text"
                    id="country"
                    name="country"
                    
                    value="{{ old('country', $laboratory->country ?? 'Nigeria') }}"
                    class="mt-1 block w-full rounded-lg border border-slate-600 bg-slate-900 text-white placeholder-slate-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">

                @error('country')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Currency Code --}}
            <div>
                <label for="currency_code" class="block text-sm font-medium text-slate-300">
                    Currency Code <span class="text-red-500">*</span>
                </label>

                <input
                    type="text"
                    id="currency_code"
                    name="currency_code"
                    value="{{ old('currency_code', $laboratory->currency_code ?? 'NGN') }}"
                    class="mt-1 block w-full rounded-lg border border-slate-600 bg-slate-900 text-white placeholder-slate-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">

                @error('currency_code')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Currency Symbol --}}
            <div>
                <label for="currency_symbol" class="block text-sm font-medium text-slate-300">
                    Currency Symbol <span class="text-red-500">*</span>
                </label>

                <input
                    type="text"
                    id="currency_symbol"
                    name="currency_symbol"
                    value="{{ old('currency_symbol', $laboratory->currency_symbol ?? '₦') }}"
                    class="mt-1 block w-full rounded-lg border border-slate-600 bg-slate-900 text-white placeholder-slate-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">

                @error('currency_symbol')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Timezone --}}
            <div>
                <label for="timezone" class="block text-sm font-medium text-slate-300">
                    Timezone
                </label>

                <input
                    type="text"
                    id="timezone"
                    name="timezone"
                    
                    value="{{ old('timezone', $laboratory->timezone ?? 'Africa/Lagos') }}"
                    class="mt-1 block w-full rounded-lg border border-slate-600 bg-slate-900 text-white placeholder-slate-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">

                @error('timezone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

        </div> 

        {{-- Address --}}
        <div class="mt-6">
            <label for="address" class="block text-sm font-medium text-slate-300">
                Address
            </label>

            <textarea
                id="address"
                name="address"
                rows="3"
                class="mt-1 block w-full rounded-lg border border-slate-600 bg-slate-900 text-white placeholder-slate-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('address', $laboratory->address ?? '') }}</textarea>

            @error('address')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Active --}}
        <div class="mt-6">
            <label class="inline-flex items-center">

                <input
                    type="checkbox"
                    name="is_active"
                    value="1"
                    
                    class="rounded border-slate-600 bg-slate-900 text-blue-600 focus:ring-blue-500"
                    {{ old('is_active', $laboratory->is_active ?? true) ? 'checked' : '' }}>

                <span class="ml-2 text-sm text-slate-300">
                    Active
                </span>

            </label>
        </div>

    </div>

    
    <div class="flex justify-end gap-3 border-t border-slate-700 bg-slate-900 px-6 py-4">

        <a href="{{ route('laboratories.index') }}"
           class="rounded-md bg-slate-600 px-4 py-2 text-white hover:bg-slate-700">
           
            Cancel
        </a>

        <button
            type="submit"
            class="rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
           
            Save Laboratory
        </button>

    </div>

</div>