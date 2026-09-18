<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-white">
                New Bank Reconciliation
            </h2>
            <p class="mt-1 text-sm text-slate-400">
                Enter the bank statement details to begin reconciliation.
            </p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">

            <form method="POST"
                  action="{{ route('accounting.bank-reconciliation.store') }}"
                  class="space-y-6">
                @csrf

                {{-- Bank & Branch --}}
                <div class="rounded-xl border border-slate-700 bg-slate-900 p-6">
                    <h3 class="mb-5 text-lg font-semibold text-white">
                        Bank Statement
                    </h3>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-300">
                                Bank Account
                            </label>

                            <select name="bank_account_id"
        required
        class="w-full rounded-lg border-slate-700 bg-slate-800 text-white focus:border-blue-500 focus:ring-blue-500">

    <option value="">Select Bank Account</option>

    @foreach ($bankAccounts as $bankAccount)
        <option value="{{ $bankAccount->id }}"
            @selected(old('bank_account_id') == $bankAccount->id)>
            {{ $bankAccount->bank_name }} — {{ $bankAccount->account_name }}
            ({{ $bankAccount->branch?->name ?? 'Whole Laboratory' }})
        </option>
    @endforeach

</select>
                            @error('bank_account_id')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-300">
                                Branch
                            </label>

                            <select name="branch_id"
                                    class="w-full rounded-lg border-slate-700 bg-slate-800 text-white focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Head Office</option>

                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}"
                                        @selected(old('branch_id') == $branch->id)>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('branch_id')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-300">
                                Statement Date
                            </label>

                            <input type="date"
                                   name="statement_date"
                                   value="{{ old('statement_date', now()->format('Y-m-d')) }}"
                                   required
                                   class="w-full rounded-lg border-slate-700 bg-slate-800 text-white focus:border-blue-500 focus:ring-blue-500">

                            <p class="mt-1 text-xs text-slate-500">
                                Use the closing date shown on the bank statement.
                            </p>

                            @error('statement_date')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- Balances --}}
                <div class="rounded-xl border border-slate-700 bg-slate-900 p-6">
                    <h3 class="mb-5 text-lg font-semibold text-white">
                        Statement Balances
                    </h3>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-300">
                                Statement Opening Balance
                            </label>

                            <input type="number"
                                   name="statement_opening_balance"
                                   value="{{ old('statement_opening_balance', 0) }}"
                                   step="0.01"
                                   min="0"
                                   required
                                   class="w-full rounded-lg border-slate-700 bg-slate-800 text-white focus:border-blue-500 focus:ring-blue-500">

                            @error('statement_opening_balance')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-300">
                                Statement Closing Balance
                            </label>

                            <input type="number"
                                   name="statement_closing_balance"
                                   value="{{ old('statement_closing_balance', 0) }}"
                                   step="0.01"
                                   min="0"
                                   required
                                   class="w-full rounded-lg border-slate-700 bg-slate-800 text-white focus:border-blue-500 focus:ring-blue-500">

                            @error('statement_closing_balance')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    <div class="mt-5 rounded-lg border border-blue-500/20 bg-blue-500/5 p-4">
                        <p class="text-sm text-slate-300">
                            <span class="font-semibold text-blue-400">Next step:</span>
                            After creating this reconciliation, you will see the bank transactions
                            recorded in the accounting system and can mark the transactions that
                            appear on the bank statement.
                        </p>
                    </div>
                </div>

                {{-- Notes --}}
                <div class="rounded-xl border border-slate-700 bg-slate-900 p-6">
                    <label class="mb-2 block text-sm font-medium text-slate-300">
                        Notes
                    </label>

                    <textarea name="notes"
                              rows="3"
                              placeholder="Optional reconciliation notes..."
                              class="w-full rounded-lg border-slate-700 bg-slate-800 text-white placeholder-slate-500 focus:border-blue-500 focus:ring-blue-500">{{ old('notes') }}</textarea>

                    @error('notes')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-between">
                    <a href="{{ route('accounting.bank-reconciliation.index') }}"
                       class="rounded-lg border border-slate-700 px-5 py-2.5 text-sm font-semibold text-slate-300 hover:bg-slate-800">
                        Cancel
                    </a>

                    <button type="submit"
                            class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-500">
                        Start Reconciliation
                    </button>
                </div>

            </form>

        </div>
    </div>
</x-app-layout>