<x-app-layout>
    <div class="max-w-3xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-white">
                Record Supplier Payment
            </h1>

            <p class="mt-1 text-sm text-slate-400">
                Record a payment made against supplier invoice
                #{{ $supplierInvoice->invoice_number }}.
            </p>
        </div>

        {{-- Invoice Summary --}}
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-6 mb-6">

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">

                <div>
                    <p class="text-sm text-slate-400">Supplier</p>
                    <p class="mt-1 font-medium text-white">
                        {{ $supplierInvoice->supplier_name }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-400">Invoice Amount</p>
                    <p class="mt-1 font-medium text-white">
                        ₦{{ number_format($supplierInvoice->amount, 2) }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-400">Outstanding</p>
                    <p class="mt-1 font-bold text-red-400">
                        ₦{{ number_format($supplierInvoice->balance(), 2) }}
                    </p>
                </div>

            </div>

        </div>

        {{-- Payment Form --}}
        <div class="bg-slate-800 rounded-xl border border-slate-700 shadow-lg">

            <form method="POST"
                   action="{{ route('accounting.accounts-payable.payment.store', $supplierInvoice) }}"
                  class="p-6 space-y-6">

                @csrf

                {{-- Amount --}}
                <div>
                    <label for="amount"
                           class="block text-sm font-medium text-slate-300 mb-1">
                        Payment Amount <span class="text-red-400">*</span>
                    </label>

                    <input type="number"
                           name="amount"
                           id="amount"
                           value="{{ old('amount') }}"
                           min="0.01"
                           max="{{ $supplierInvoice->balance() }}"
                           step="0.01"
                           required
                           class="w-full rounded-lg bg-slate-700 border-slate-600 text-white placeholder-slate-400 focus:border-blue-500 focus:ring-blue-500">

                    <p class="mt-1 text-xs text-slate-500">
                        Maximum payment:
                        ₦{{ number_format($supplierInvoice->balance(), 2) }}
                    </p>

                    @error('amount')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Payment Method --}}
                <div>
                    <label for="payment_method"
                           class="block text-sm font-medium text-slate-300 mb-1">
                        Payment Method <span class="text-red-400">*</span>
                    </label>

                    <select name="payment_method"
                            id="payment_method"
                            required
                            class="w-full rounded-lg bg-slate-700 border-slate-600 text-white focus:border-blue-500 focus:ring-blue-500">

                        <option value="">Select payment method</option>

                        <option value="Cash"
                            {{ old('payment_method') === 'Cash' ? 'selected' : '' }}>
                            Cash
                        </option>

                        <option value="Transfer"
                            {{ old('payment_method') === 'Transfer' ? 'selected' : '' }}>
                            Transfer
                        </option>

                        <option value="POS"
                            {{ old('payment_method') === 'POS' ? 'selected' : '' }}>
                            POS
                        </option>

                        <option value="Other"
                            {{ old('payment_method') === 'Other' ? 'selected' : '' }}>
                            Other
                        </option>

                    </select>

                    @error('payment_method')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Payment Reference --}}
                <div>
                    <label for="payment_reference"
                           class="block text-sm font-medium text-slate-300 mb-1">
                        Payment Reference
                    </label>

                    <input type="text"
                           name="payment_reference"
                           id="payment_reference"
                           value="{{ old('payment_reference') }}"
                           placeholder="e.g. bank transfer reference"
                           class="w-full rounded-lg bg-slate-700 border-slate-600 text-white placeholder-slate-400 focus:border-blue-500 focus:ring-blue-500">

                    @error('payment_reference')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Payment Date --}}
                <div>
                    <label for="paid_at"
                           class="block text-sm font-medium text-slate-300 mb-1">
                        Payment Date <span class="text-red-400">*</span>
                    </label>

                    <input type="datetime-local"
                           name="paid_at"
                           id="paid_at"
                           value="{{ old('paid_at', now()->format('Y-m-d\TH:i')) }}"
                           required
                           class="w-full rounded-lg bg-slate-700 border-slate-600 text-white focus:border-blue-500 focus:ring-blue-500">

                    @error('paid_at')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remarks --}}
                <div>
                    <label for="remarks"
                           class="block text-sm font-medium text-slate-300 mb-1">
                        Remarks
                    </label>

                    <textarea name="remarks"
                              id="remarks"
                              rows="3"
                              placeholder="Optional payment remarks"
                              class="w-full rounded-lg bg-slate-700 border-slate-600 text-white placeholder-slate-400 focus:border-blue-500 focus:ring-blue-500">{{ old('remarks') }}</textarea>

                    @error('remarks')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Actions --}}
                <div class="flex flex-col sm:flex-row sm:justify-end gap-3 pt-4 border-t border-slate-700">

                    <a href="{{ route('accounting.accounts-payable.show', $supplierInvoice) }}"
                       class="inline-flex justify-center items-center rounded-lg bg-slate-700 px-5 py-2.5 text-sm font-medium text-slate-200 hover:bg-slate-600 transition">
                        Cancel
                    </a>

                    <button type="submit"
                            class="inline-flex justify-center items-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700 transition">
                        Save Payment
                    </button>

                </div>

            </form>

        </div>

    </div>
</x-app-layout>