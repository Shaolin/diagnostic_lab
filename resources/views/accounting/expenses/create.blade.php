<x-app-layout>

    <div class="p-6">

        {{-- Header --}}
        <div class="mb-6">
            <a href="{{ route('accounting.expenses') }}"
               class="text-sm text-slate-400 hover:text-white">
                ← Back to Expenses
            </a>

            <h1 class="mt-3 text-2xl font-bold text-white">
                Record Operating Expense
            </h1>

            <p class="mt-1 text-sm text-slate-400">
                Record an expense paid directly by the laboratory.
            </p>
        </div>

        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="mb-6 rounded-lg border border-red-500/30 bg-red-500/10 p-4">
                <ul class="list-inside list-disc text-sm text-red-300">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="max-w-4xl rounded-xl border border-slate-700 bg-slate-800 p-6">

            <form method="POST" action="{{ route('accounting.expenses.store') }}">
                @csrf

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                    {{-- Branch --}}
                    <div>
                        <label for="branch_id"
                               class="mb-2 block text-sm font-medium text-slate-300">
                            Branch
                        </label>

                        <select name="branch_id"
                                id="branch_id"
                                class="w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-2.5 text-sm text-white focus:border-blue-500 focus:ring-blue-500">

                            <option value="">Head Office</option>

                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}"
                                    {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach

                        </select>

                        <p class="mt-1 text-xs text-slate-500">
                            Select the branch that incurred the expense.
                        </p>
                    </div>

                    {{-- Expense Account --}}
                    <div>
                        <label for="expense_account_id"
                               class="mb-2 block text-sm font-medium text-slate-300">
                            Expense Account <span class="text-red-400">*</span>
                        </label>

                        <select name="expense_account_id"
                                id="expense_account_id"
                                required
                                class="w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-2.5 text-sm text-white focus:border-blue-500 focus:ring-blue-500">

                            <option value="">Select Expense Account</option>

                            @foreach($expenseAccounts as $account)
                                <option value="{{ $account->id }}"
                                    {{ old('expense_account_id') == $account->id ? 'selected' : '' }}>
                                    {{ $account->code }} - {{ $account->name }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    {{-- Amount --}}
                    <div>
                        <label for="amount"
                               class="mb-2 block text-sm font-medium text-slate-300">
                            Amount <span class="text-red-400">*</span>
                        </label>

                        <input type="number"
                               name="amount"
                               id="amount"
                               step="0.01"
                               min="0.01"
                               value="{{ old('amount') }}"
                               required
                               placeholder="0.00"
                               class="w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    {{-- Payment Method --}}
                    <div>
                        <label for="payment_method"
                               class="mb-2 block text-sm font-medium text-slate-300">
                            Payment Method <span class="text-red-400">*</span>
                        </label>

                        <select name="payment_method"
                                id="payment_method"
                                required
                                class="w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-2.5 text-sm text-white focus:border-blue-500 focus:ring-blue-500">

                            <option value="">Select Payment Method</option>
                            <option value="Cash" {{ old('payment_method') === 'Cash' ? 'selected' : '' }}>
                                Cash
                            </option>
                            <option value="Transfer" {{ old('payment_method') === 'Transfer' ? 'selected' : '' }}>
                                Bank Transfer
                            </option>
                            <option value="POS" {{ old('payment_method') === 'POS' ? 'selected' : '' }}>
                                POS
                            </option>
                            <option value="Other" {{ old('payment_method') === 'Other' ? 'selected' : '' }}>
                                Other
                            </option>

                        </select>
                    </div>

                    {{-- Expense Date --}}
                    <div>
                        <label for="expense_date"
                               class="mb-2 block text-sm font-medium text-slate-300">
                            Expense Date <span class="text-red-400">*</span>
                        </label>

                        <input type="date"
                               name="expense_date"
                               id="expense_date"
                               value="{{ old('expense_date', now()->toDateString()) }}"
                               required
                               class="w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-2.5 text-sm text-white focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    {{-- Payment Reference --}}
                    <div>
                        <label for="payment_reference"
                               class="mb-2 block text-sm font-medium text-slate-300">
                            Payment Reference
                        </label>

                        <input type="text"
                               name="payment_reference"
                               id="payment_reference"
                               value="{{ old('payment_reference') }}"
                               placeholder="Receipt or transaction reference"
                               class="w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    {{-- Description --}}
                    <div class="md:col-span-2">
                        <label for="description"
                               class="mb-2 block text-sm font-medium text-slate-300">
                            Description / Remarks
                        </label>

                        <textarea name="description"
                                  id="description"
                                  rows="4"
                                  placeholder="Describe the expense..."
                                  class="w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:border-blue-500 focus:ring-blue-500">{{ old('description') }}</textarea>
                    </div>

                </div>

                {{-- Actions --}}
                <div class="mt-8 flex items-center justify-end gap-3 border-t border-slate-700 pt-6">

                    <a href="{{ route('accounting.expenses') }}"
                       class="rounded-lg border border-slate-600 px-5 py-2.5 text-sm font-semibold text-slate-300 hover:bg-slate-700 hover:text-white">
                        Cancel
                    </a>

                    <button type="submit"
                            class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg hover:bg-blue-700">
                        Record Expense
                    </button>

                </div>

            </form>

        </div>

    </div>

</x-app-layout>