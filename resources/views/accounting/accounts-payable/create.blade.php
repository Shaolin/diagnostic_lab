<x-app-layout>
    <div class="max-w-5xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-white">
                    Add Supplier Invoice
                </h1>

                <p class="text-sm text-slate-400 mt-1">
                    Record a bill received from a supplier.
                </p>
            </div>

            <a href="{{ route('accounting.accounts-payable') }}"
               class="inline-flex items-center justify-center rounded-lg bg-slate-700 px-4 py-2 text-sm font-medium text-white hover:bg-slate-600 transition">
                ← Back to Accounts Payable
            </a>
        </div>

        {{-- Form --}}
        <div class="bg-slate-800 rounded-xl border border-slate-700 shadow-lg">
            <form method="POST"
                  action="{{ route('accounting.accounts-payable.store') }}"
                  class="p-6 space-y-6">

                @csrf

                {{-- Supplier Information --}}
                <div>
                    <h2 class="text-lg font-semibold text-white mb-4">
                        Supplier Information
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        {{-- Supplier Name --}}
                        <div>
                            <label for="supplier_name"
                                   class="block text-sm font-medium text-slate-300 mb-1">
                                Supplier Name <span class="text-red-400">*</span>
                            </label>

                            <input type="text"
                                   name="supplier_name"
                                   id="supplier_name"
                                   value="{{ old('supplier_name') }}"
                                   required
                                   class="w-full rounded-lg bg-slate-700 border-slate-600 text-white placeholder-slate-400 focus:border-blue-500 focus:ring-blue-500">

                            @error('supplier_name')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Invoice Number --}}
                        <div>
                            <label for="invoice_number"
                                   class="block text-sm font-medium text-slate-300 mb-1">
                                Invoice Number <span class="text-red-400">*</span>
                            </label>

                            <input type="text"
                                   name="invoice_number"
                                   id="invoice_number"
                                   value="{{ old('invoice_number') }}"
                                   required
                                   class="w-full rounded-lg bg-slate-700 border-slate-600 text-white placeholder-slate-400 focus:border-blue-500 focus:ring-blue-500">

                            @error('invoice_number')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Phone --}}
                        <div>
                            <label for="supplier_phone"
                                   class="block text-sm font-medium text-slate-300 mb-1">
                                Supplier Phone
                            </label>

                            <input type="text"
                                   name="supplier_phone"
                                   id="supplier_phone"
                                   value="{{ old('supplier_phone') }}"
                                   class="w-full rounded-lg bg-slate-700 border-slate-600 text-white placeholder-slate-400 focus:border-blue-500 focus:ring-blue-500">

                            @error('supplier_phone')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="supplier_email"
                                   class="block text-sm font-medium text-slate-300 mb-1">
                                Supplier Email
                            </label>

                            <input type="email"
                                   name="supplier_email"
                                   id="supplier_email"
                                   value="{{ old('supplier_email') }}"
                                   class="w-full rounded-lg bg-slate-700 border-slate-600 text-white placeholder-slate-400 focus:border-blue-500 focus:ring-blue-500">

                            @error('supplier_email')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>

                <hr class="border-slate-700">

                {{-- Invoice Details --}}
                <div>
                    <h2 class="text-lg font-semibold text-white mb-4">
                        Invoice Details
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        {{-- Branch --}}
                        <div>
                            <label for="branch_id"
                                   class="block text-sm font-medium text-slate-300 mb-1">
                                Branch
                            </label>

                            <select name="branch_id"
                                    id="branch_id"
                                    class="w-full rounded-lg bg-slate-700 border-slate-600 text-white focus:border-blue-500 focus:ring-blue-500">

                                <option value="" class="bg-slate-700">
                                    Head Office / General
                                </option>

                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}"
                                        {{ old('branch_id') == $branch->id ? 'selected' : '' }}
                                        class="bg-slate-700">
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('branch_id')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
    <label for="expense_account_id" class="block text-sm font-medium text-slate-300 mb-2">
        Expense Account
    </label>

    <select
        name="expense_account_id"
        id="expense_account_id"
        required
        class="w-full rounded-lg border border-slate-700 bg-slate-800 px-4 py-2.5 text-white focus:border-blue-500 focus:ring-blue-500"
    >
        <option value="">Select expense account</option>

        @foreach($expenseAccounts as $account)
            <option value="{{ $account->id }}"
                {{ old('expense_account_id') == $account->id ? 'selected' : '' }}>
                {{ $account->code }} - {{ $account->name }}
            </option>
        @endforeach
    </select>

    @error('expense_account_id')
        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
    @enderror
</div>

                        {{-- Amount --}}
                        <div>
                            <label for="amount"
                                   class="block text-sm font-medium text-slate-300 mb-1">
                                Invoice Amount <span class="text-red-400">*</span>
                            </label>

                            <input type="number"
                                   name="amount"
                                   id="amount"
                                   value="{{ old('amount') }}"
                                   min="0.01"
                                   step="0.01"
                                   required
                                   class="w-full rounded-lg bg-slate-700 border-slate-600 text-white placeholder-slate-400 focus:border-blue-500 focus:ring-blue-500">

                            @error('amount')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Invoice Date --}}
                        <div>
                            <label for="invoice_date"
                                   class="block text-sm font-medium text-slate-300 mb-1">
                                Invoice Date <span class="text-red-400">*</span>
                            </label>

                            <input type="date"
                                   name="invoice_date"
                                   id="invoice_date"
                                   value="{{ old('invoice_date', now()->format('Y-m-d')) }}"
                                   required
                                   class="w-full rounded-lg bg-slate-700 border-slate-600 text-white focus:border-blue-500 focus:ring-blue-500">

                            @error('invoice_date')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Due Date --}}
                        <div>
                            <label for="due_date"
                                   class="block text-sm font-medium text-slate-300 mb-1">
                                Due Date
                            </label>

                            <input type="date"
                                   name="due_date"
                                   id="due_date"
                                   value="{{ old('due_date') }}"
                                   class="w-full rounded-lg bg-slate-700 border-slate-600 text-white focus:border-blue-500 focus:ring-blue-500">

                            @error('due_date')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="md:col-span-2">
                            <label for="description"
                                   class="block text-sm font-medium text-slate-300 mb-1">
                                Description
                            </label>

                            <textarea name="description"
                                      id="description"
                                      rows="3"
                                      class="w-full rounded-lg bg-slate-700 border-slate-600 text-white placeholder-slate-400 focus:border-blue-500 focus:ring-blue-500"
                                      placeholder="e.g. Laboratory reagents supplied">{{ old('description') }}</textarea>

                            @error('description')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex flex-col sm:flex-row sm:justify-end gap-3 pt-4 border-t border-slate-700">

                    <a href="{{ route('accounting.accounts-payable') }}"
                       class="inline-flex justify-center items-center rounded-lg bg-slate-700 px-5 py-2.5 text-sm font-medium text-slate-200 hover:bg-slate-600 transition">
                        Cancel
                    </a>

                    <button type="submit"
                            class="inline-flex justify-center items-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700 transition">
                        Save Supplier Invoice
                    </button>

                </div>

            </form>
        </div>

    </div>
</x-app-layout>