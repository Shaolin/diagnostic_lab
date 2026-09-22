<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-white">
                Correct Opening Balance
            </h2>

            <p class="mt-1 text-sm text-slate-400">
                Correct the opening balance for {{ $bankAccount->bank_name }}.
            </p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">

            <div class="rounded-xl border border-slate-700 bg-slate-900 p-6">

                <div class="mb-6 rounded-lg border border-amber-500/20 bg-amber-500/10 p-4">
                    <p class="text-sm leading-6 text-slate-300">
                        <span class="font-semibold">Important:</span>
                        This will create an accounting adjustment. The original
                        opening balance entry will remain in the ledger for audit purposes.
                    </p>
                </div>

                <div class="mb-6 grid gap-4 sm:grid-cols-2">

                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-500">
                            Bank
                        </p>

                        <p class="mt-1 font-medium text-white">
                            {{ $bankAccount->bank_name }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-500">
                            Account Name
                        </p>

                        <p class="mt-1 font-medium text-white">
                            {{ $bankAccount->account_name }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-500">
                            Current Opening Balance
                        </p>

                        <p class="mt-1 text-lg font-semibold text-white">
                            ₦{{ number_format((float) $bankAccount->opening_balance, 2) }}
                        </p>
                    </div>

                </div>

                <form method="POST"
                      action="{{ route('accounting.bank-accounts.update-opening-balance', $bankAccount) }}"
                      class="space-y-6">

                    @csrf
                    @method('PUT')

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-300">
                            Correct Opening Balance
                        </label>

                        <input
                            type="number"
                            name="opening_balance"
                            value="{{ old('opening_balance', $bankAccount->opening_balance) }}"
                            step="0.01"
                            min="0"
                            required
                            class="w-full rounded-lg border-slate-700 bg-slate-800 text-white placeholder-slate-500 focus:border-blue-500 focus:ring-blue-500"
                        >

                        @error('opening_balance')
                            <p class="mt-1 text-sm text-red-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-300">
                            Reason for Correction
                        </label>

                        <textarea
                            name="reason"
                            rows="4"
                            required
                            placeholder="e.g. Opening balance was entered as ₦2,000,000 instead of ₦1,000,000."
                            class="w-full rounded-lg border-slate-700 bg-slate-800 text-white placeholder-slate-500 focus:border-blue-500 focus:ring-blue-500"
                        >{{ old('reason') }}</textarea>

                        @error('reason')
                            <p class="mt-1 text-sm text-red-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">

                        <a href="{{ route('accounting.bank-accounts.index') }}"
                           class="rounded-lg border border-slate-700 px-5 py-2.5 text-sm font-semibold text-slate-300 hover:bg-slate-800">
                            Cancel
                        </a>

                        <button type="submit"
                                class="rounded-lg bg-amber-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-amber-500">
                            Correct Opening Balance
                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>
</x-app-layout>