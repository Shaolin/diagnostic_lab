<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-white">
                Edit Bank Account
            </h2>

            <p class="mt-1 text-sm text-slate-400">
                Update the bank account details.
            </p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">

            <form method="POST"
                  action="{{ route('accounting.bank-accounts.update', $bankAccount) }}"
                  class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Bank Details --}}
                <div class="rounded-xl border border-slate-700 bg-slate-900 p-6">

                    <h3 class="mb-5 text-lg font-semibold text-white">
                        Bank Details
                    </h3>

                    <div class="space-y-5">

                        {{-- Bank Name --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-300">
                                Bank Name
                            </label>

                            <input type="text"
                                   name="bank_name"
                                   value="{{ old('bank_name', $bankAccount->bank_name) }}"
                                   required
                                   class="w-full rounded-lg border-slate-700 bg-slate-800 text-white placeholder-slate-500 focus:border-blue-500 focus:ring-blue-500">

                            @error('bank_name')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Account Name --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-300">
                                Account Name
                            </label>

                            <input type="text"
                                   name="account_name"
                                   value="{{ old('account_name', $bankAccount->account_name) }}"
                                   required
                                   class="w-full rounded-lg border-slate-700 bg-slate-800 text-white placeholder-slate-500 focus:border-blue-500 focus:ring-blue-500">

                            @error('account_name')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Account Number --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-300">
                                Account Number
                            </label>

                            <input type="text"
                                   name="account_number"
                                   value="{{ old('account_number', $bankAccount->account_number) }}"
                                   class="w-full rounded-lg border-slate-700 bg-slate-800 text-white placeholder-slate-500 focus:border-blue-500 focus:ring-blue-500">

                            @error('account_number')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Branch --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-300">
                                Branch / Scope
                            </label>

                            <select name="branch_id"
                                    class="w-full rounded-lg border-slate-700 bg-slate-800 text-white focus:border-blue-500 focus:ring-blue-500">

                                <option value="">
                                    Whole Laboratory — Shared Account
                                </option>

                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}"
                                        @selected(old('branch_id', $bankAccount->branch_id) == $branch->id)>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach

                            </select>

                            <p class="mt-1 text-xs text-slate-500">
                                Choose a branch if the account belongs specifically to that branch.
                                Leave it as Whole Laboratory if the same bank account serves all branches.
                            </p>

                            @error('branch_id')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Opening Balance --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-300">
                                Opening Balance
                            </label>

                            <input type="text"
                                   value="₦{{ number_format((float) $bankAccount->opening_balance, 2) }}"
                                   readonly
                                   class="w-full cursor-not-allowed rounded-lg border-slate-700 bg-slate-800 text-slate-400">

                            <p class="mt-1 text-xs text-slate-500">
                                The opening balance cannot be changed after the account has been posted
                                to the accounting ledger.
                            </p>
                        </div>

                    </div>
                </div>

                {{-- Information --}}
                <div class="rounded-xl border border-amber-500/20 bg-amber-500/5 p-5">
                    <p class="text-sm leading-6 text-slate-300">
                        <span class="font-semibold text-amber-400">Accounting Note:</span>
                        Changing the opening balance after posting would make the bank account
                        and accounting ledger disagree. To correct an opening balance, delete
                        the account and create it again with the correct amount.
                    </p>
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-between">

                    <a href="{{ route('accounting.bank-accounts.index') }}"
                       class="rounded-lg border border-slate-700 px-5 py-2.5 text-sm font-semibold text-slate-300 hover:bg-slate-800">
                        Cancel
                    </a>

                    <button type="submit"
                            class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-500">
                        Update Bank Account
                    </button>

                </div>

            </form>

        </div>
    </div>
</x-app-layout>