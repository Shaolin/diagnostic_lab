<?php

namespace App\Http\Controllers;
   use App\Models\User;
use Illuminate\Http\Request;
 use App\Enums\UserRole;
 use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;



class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    

public function index(Request $request)
{
    $this->authorize('viewAny', User::class);
   $users = User::query()
    ->with('laboratory')

    ->when(
        ! auth()->user()->isSuperAdmin(),
        function ($query) {
            $query->where(
                'laboratory_id',
                auth()->user()->laboratory_id
            );
        }
    )
        ->when($request->filled('search'), function ($query) use ($request) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        })
        ->when($request->filled('role'), function ($query) use ($request) {
            $query->where('role', $request->role);
        })
        ->when($request->filled('status'), function ($query) use ($request) {
            $query->where('is_active', $request->boolean('status'));
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

  $roles = auth()->user()->isSuperAdmin()
    ? UserRole::cases()
    : [
        UserRole::ADMIN,
        UserRole::ACCOUNTANT,
        UserRole::STAFF,
    ];
  return view('users.index', compact('users', 'roles'));
}

    /**
     * Show the form for creating a new resource.
     */
  





public function create()
{
    $this->authorize('create', User::class);

   $roles = [
    UserRole::ADMIN,
    UserRole::ACCOUNTANT,
    UserRole::STAFF,
];

    $branches = auth()->user()->laboratory->branches()
        ->where('is_active', true)
        ->orderBy('name')
        ->get();

    return view('users.create', compact('roles', 'branches'));
}



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
{
    $this->authorize('create', User::class);
    $data = $request->validated();

    // Always assign the authenticated user's laboratory
    $data['laboratory_id'] = auth()->user()->laboratory_id;

    User::create($data);

    return redirect()
        ->route('users.index')
        ->with('success', 'User created successfully.');
}

    /**
     * Display the specified resource.
//      */


public function show(User $user)
{
    $this->authorize('view', $user);

    $user->load('branch');

    return view('users.show', compact('user'));
}
    /**
     * Show the form for editing the specified resource.
     */





public function edit(User $user)
{
    $this->authorize('update', $user);

   $roles = [
    UserRole::ADMIN,
    UserRole::ACCOUNTANT,
    UserRole::STAFF,
];

    $branches = auth()->user()->laboratory->branches()
        ->where('is_active', true)
        ->orderBy('name')
        ->get();

    return view('users.edit', compact('user', 'roles', 'branches'));
}



    /**
     * Update the specified resource in storage.
     */
   public function update(UpdateUserRequest $request, User $user)
{
    $this->authorize('update', $user);

    $data = $request->validated();

    // Keep the existing password if no new password was provided
    if (blank($data['password'])) {
        unset($data['password']);
    }

    $user->update($data);

    return redirect()
        ->route('users.index')
        ->with('success', 'User updated successfully.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
{
    $this->authorize('delete', $user);

    $user->update([
        'is_active' => false,
    ]);

    return redirect()
        ->route('users.index')
        ->with('success', 'User has been deactivated successfully.');
}
public function activate(User $user)
{
    $this->authorize('update', $user);

    $user->update([
        'is_active' => true,
    ]);

    return redirect()
        ->route('users.index')
        ->with('success', 'User has been activated successfully.');
}
}


// user/edit.blade.php  
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h2 class="text-xl font-semibold text-white">
                    Edit User
                </h2>

                <p class="mt-1 text-sm text-slate-400">
                    Update this user's information.
                </p>
            </div>

            <a href="{{ route('users.index') }}"
               class="inline-flex items-center justify-center rounded-md bg-slate-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-slate-700">
                ← Back to Users
            </a>

        </div>
    </x-slot>

    <div class="py-8 bg-slate-900 min-h-screen">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">

            {{-- Validation Errors --}}
            @if ($errors->any())
    <div class="mb-6 rounded-lg border border-red-700 bg-red-900/30 px-4 py-3 text-red-300 shadow-sm">

        <p class="font-semibold">
            Please correct the following errors:
        </p>

        <ul class="mt-2 list-disc pl-5 text-sm">

            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach

        </ul>

    </div>
@endif

            {{-- User Form --}}
            <div class="rounded-xl border border-slate-700 bg-slate-800 shadow-xl">

   
     <form action="{{ route('users.update', $user) }}" method="POST">
    @csrf
    @method('PUT')

        

        <div class="p-6">

            {{-- Form Fields Here --}}
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

    {{-- Full Name --}}
    <div>
        <label for="name" class="mb-2 block text-sm font-medium text-slate-300">
            Full Name
        </label>

        <input
            type="text"
            id="name"
            name="name"
            value="{{ old('name', $user->name) }}"
            class="w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-2 text-white placeholder-slate-400 focus:border-indigo-500 focus:ring-indigo-500"
            required
        >
    </div>

    {{-- Email --}}
    <div>
        <label for="email" class="mb-2 block text-sm font-medium text-slate-300">
            Email Address
        </label>

        <input
            type="email"
            id="email"
            name="email"
            value="{{ old('email', $user->email) }}"
            class="w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-2 text-white placeholder-slate-400 focus:border-indigo-500 focus:ring-indigo-500"
            required
        >
    </div>

    {{-- Role --}}
    <div>
        <label for="role" class="mb-2 block text-sm font-medium text-slate-300">
            Role
        </label>

        <select
            id="role"
            name="role"
            class="w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-2 text-white focus:border-indigo-500 focus:ring-indigo-500"
            required
        >
            <option value="">Select Role</option>

            @foreach ($roles as $role)
                <option
                    value="{{ $role->value }}"
                    @selected(old('role', $user->role->value) == $role->value)
                >
                    {{ $role->label() }}
                </option>
            @endforeach

        </select>
    </div>

    
{{-- Branch --}}
<div>
    <label for="branch_id" class="mb-2 block text-sm font-medium text-slate-300">
        Branch
    </label>

    <select
        id="branch_id"
        name="branch_id"
        class="w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-2 text-white focus:border-indigo-500 focus:ring-indigo-500"
    >
        <option value="">Select Branch</option>

        @foreach ($branches as $branch)
            <option
                value="{{ $branch->id }}"
                @selected(old('branch_id', $user->branch_id) == $branch->id)
            >
                {{ $branch->name }}
            </option>
        @endforeach
    </select>

    <p id="branch-help" class="mt-1 text-xs text-slate-400 hidden">
        Accountants have access to all branches.
    </p>

    @error('branch_id')
        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
    @enderror
</div>

    {{-- Active --}}
    <div class="flex items-end">
        <label class="inline-flex items-center gap-3 text-slate-300">
            <input
                type="checkbox"
                name="is_active"
                value="1"
                @checked(old('is_active', $user->is_active))
                class="rounded border-slate-500 bg-slate-900 text-indigo-600 focus:ring-indigo-500"
            >

            Active User
        </label>
    </div>

    {{-- Password --}}
    <div>
        <label for="password" class="mb-2 block text-sm font-medium text-slate-300">
            Password
        </label>

        <input
            type="password"
            id="password"
            name="password"
            class="w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-2 text-white focus:border-indigo-500 focus:ring-indigo-500"
            
        >
    </div>

    {{-- Confirm Password --}}
    <div>
        <label for="password_confirmation" class="mb-2 block text-sm font-medium text-slate-300">
            Confirm Password
        </label>

        <input
            type="password"
            id="password_confirmation"
            name="password_confirmation"
            class="w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-2 text-white focus:border-indigo-500 focus:ring-indigo-500"
            
        >
        <p class="mt-2 text-sm text-slate-400">
    Leave blank to keep the current password.
</p>
    </div>

</div>

        </div>

        <div class="flex justify-end gap-3 border-t border-slate-700 p-6">

            <a href="{{ route('users.index') }}"
               class="rounded-md bg-slate-600 px-5 py-2 text-white hover:bg-slate-700">
                Cancel
            </a>

            <button
                type="submit"
                class="rounded-md bg-indigo-600 px-5 py-2 text-white hover:bg-indigo-700">

                Update User

            </button>

        </div>

    </form>

</div>

        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const role = document.getElementById('role');
        const branch = document.getElementById('branch_id');
        const branchHelp = document.getElementById('branch-help');

        function updateBranchField() {
            if (role.value === 'accountant') {
                branch.value = '';
                branch.disabled = true;
                branchHelp.classList.remove('hidden');
            } else {
                branch.disabled = false;
                branchHelp.classList.add('hidden');
            }
        }

        role.addEventListener('change', updateBranchField);

        updateBranchField();
    });
</script>
</x-app-layout>

// sidebar

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
            @if(auth()->user()->isAdmin() || auth()->user()->isAccountant())
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