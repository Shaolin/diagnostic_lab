
<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-slate-100 leading-tight">
                Record Petty Cash Transaction
            </h2>

            <p class="text-sm text-slate-400 mt-1">
                {{ $pettyCashFund->name }} · {{ $pettyCashFund->branch->name }}
            </p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            {{-- Fund Balance --}}
            <div class="mb-6 bg-slate-800 border border-slate-700 rounded-xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-400">
                            Current Petty Cash Balance
                        </p>

                        <p class="mt-1 text-2xl font-bold text-slate-100">
                            ₦{{ number_format((float) $pettyCashFund->current_balance, 2) }}
                        </p>
                    </div>

                    <div class="text-right">
                        <p class="text-sm text-slate-400">
                            Custodian
                        </p>

                        <p class="mt-1 text-sm font-medium text-slate-200">
                            {{ $pettyCashFund->custodian->name }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <div class="bg-slate-800 border border-slate-700 rounded-xl overflow-hidden">

                <div class="px-6 py-5 border-b border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-100">
                        Transaction Details
                    </h3>

                    <p class="text-sm text-slate-400 mt-1">
                        Record money spent from or added to this petty cash fund.
                    </p>
                </div>

                <form method="POST"
                      action="{{ route('accounting.petty-cash.transactions.store', $pettyCashFund) }}"
                      class="p-6 space-y-6">

                    @csrf

                    {{-- Transaction Type --}}
                    <div>
                        <label for="type"
                               class="block text-sm font-medium text-slate-300 mb-2">
                            Transaction Type
                        </label>

                        <select name="type"
                                id="type"
                                required
                                class="w-full rounded-lg bg-slate-900 border-slate-700 text-slate-100 focus:border-indigo-500 focus:ring-indigo-500">

                            <option value="">Select Transaction Type</option>

                            <option value="expense"
                                {{ old('type') === 'expense' ? 'selected' : '' }}>
                                Expense
                            </option>

                            <option value="replenishment"
                                {{ old('type') === 'replenishment' ? 'selected' : '' }}>
                                Replenishment
                            </option>

                        </select>

                        @error('type')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror

                        <p class="mt-1 text-xs text-slate-500">
                            Expense reduces the petty cash balance. Replenishment increases it.
                        </p>
                    </div>

                    {{-- Amount --}}
                    <div>
                        <label for="amount"
                               class="block text-sm font-medium text-slate-300 mb-2">
                            Amount
                        </label>

                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                ₦
                            </span>

                            <input type="number"
                                   name="amount"
                                   id="amount"
                                   value="{{ old('amount') }}"
                                   min="0.01"
                                   step="0.01"
                                   required
                                   class="w-full rounded-lg bg-slate-900 border-slate-700 text-slate-100 pl-8 placeholder-slate-500 focus:border-indigo-500 focus:ring-indigo-500">

                        </div>

                        @error('amount')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Expense Account --}}
                    <div id="expense-account-field">
                        <label for="account_id"
                               class="block text-sm font-medium text-slate-300 mb-2">
                            Expense Account
                        </label>

                        <select name="account_id"
                                id="account_id"
                                class="w-full rounded-lg bg-slate-900 border-slate-700 text-slate-100 focus:border-indigo-500 focus:ring-indigo-500">

                            <option value="">Select Expense Account</option>

                            @foreach ($expenseAccounts as $account)
                                <option value="{{ $account->id }}"
                                    {{ old('account_id') == $account->id ? 'selected' : '' }}>
                                    {{ $account->name }}
                                </option>
                            @endforeach

                        </select>

                        @error('account_id')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Source Account --}}
                    <div id="source-account-field" class="hidden">
                        <label for="source_account_id"
                               class="block text-sm font-medium text-slate-300 mb-2">
                            Source Cash / Bank Account
                        </label>

                        <select name="source_account_id"
                                id="source_account_id"
                                class="w-full rounded-lg bg-slate-900 border-slate-700 text-slate-100 focus:border-indigo-500 focus:ring-indigo-500">

                            <option value="">Select Source Account</option>

                            @foreach ($sourceAccounts as $account)
                                <option value="{{ $account->id }}"
                                    {{ old('source_account_id') == $account->id ? 'selected' : '' }}>
                                    {{ $account->name }}
                                </option>
                            @endforeach

                        </select>

                        @error('source_account_id')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="description"
                               class="block text-sm font-medium text-slate-300 mb-2">
                            Description
                        </label>

                        <input type="text"
                               name="description"
                               id="description"
                               value="{{ old('description') }}"
                               placeholder="e.g. Purchase of stationery"
                               required
                               class="w-full rounded-lg bg-slate-900 border-slate-700 text-slate-100 placeholder-slate-500 focus:border-indigo-500 focus:ring-indigo-500">

                        @error('description')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Reference --}}
                    <div>
                        <label for="reference"
                               class="block text-sm font-medium text-slate-300 mb-2">
                            Reference
                            <span class="text-slate-500">(Optional)</span>
                        </label>

                        <input type="text"
                               name="reference"
                               id="reference"
                               value="{{ old('reference') }}"
                               placeholder="e.g. Receipt No. 00125"
                               class="w-full rounded-lg bg-slate-900 border-slate-700 text-slate-100 placeholder-slate-500 focus:border-indigo-500 focus:ring-indigo-500">

                        @error('reference')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Transaction Date --}}
                    <div>
                        <label for="transaction_date"
                               class="block text-sm font-medium text-slate-300 mb-2">
                            Transaction Date
                        </label>

                        <input type="date"
                               name="transaction_date"
                               id="transaction_date"
                               value="{{ old('transaction_date', now()->format('Y-m-d')) }}"
                               required
                               class="w-full rounded-lg bg-slate-900 border-slate-700 text-slate-100 focus:border-indigo-500 focus:ring-indigo-500">

                        @error('transaction_date')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Buttons --}}
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-700">

                        <a href="{{ route('accounting.petty-cash.transactions.index', $pettyCashFund) }}"
                           class="inline-flex items-center px-4 py-2 rounded-lg border border-slate-600 text-sm font-medium text-slate-300 hover:bg-slate-700">
                            Cancel
                        </a>

                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 rounded-lg bg-indigo-600 text-sm font-semibold text-white hover:bg-indigo-500">
                            Record Transaction
                        </button>

                    </div>

                </form>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const type = document.getElementById('type');
            const expenseField = document.getElementById('expense-account-field');
            const sourceField = document.getElementById('source-account-field');

            const account = document.getElementById('account_id');
            const sourceAccount = document.getElementById('source_account_id');

            function updateFields() {
                if (type.value === 'replenishment') {
                    expenseField.classList.add('hidden');
                    sourceField.classList.remove('hidden');

                    account.value = '';
                } else {
                    expenseField.classList.remove('hidden');
                    sourceField.classList.add('hidden');

                    sourceAccount.value = '';
                }
            }

            type.addEventListener('change', updateFields);

            updateFields();
        });
    </script>

</x-app-layout>

