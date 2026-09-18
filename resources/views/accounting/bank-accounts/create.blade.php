<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-white">
                Add Bank Account
            </h2>

            <p class="mt-1 text-sm text-slate-400">
                Add a bank account used by the laboratory.
            </p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">

            <form method="POST"
                  action="{{ route('accounting.bank-accounts.store') }}"
                  class="space-y-6">
                @csrf

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
                                   value="{{ old('bank_name') }}"
                                   placeholder="e.g. GTBank"
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
                                   value="{{ old('account_name') }}"
                                   placeholder="e.g. ABC Diagnostic Laboratory"
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
                                   value="{{ old('account_number') }}"
                                   placeholder="e.g. 0123456789"
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
                                        @selected(old('branch_id') == $branch->id)>
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

                            <input type="number"
                                   name="opening_balance"
                                   value="{{ old('opening_balance', 0) }}"
                                   step="0.01"
                                   min="0"
                                   required
                                   class="w-full rounded-lg border-slate-700 bg-slate-800 text-white placeholder-slate-500 focus:border-blue-500 focus:ring-blue-500">

                            <p class="mt-1 text-xs text-slate-500">
                                The balance in this bank account when you start using the system.
                            </p>

                            @error('opening_balance')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- Information --}}
                <div class="rounded-xl border border-blue-500/20 bg-blue-500/5 p-5">
                    <p class="text-sm leading-6 text-slate-300">
                        <span class="font-semibold text-blue-400">Note:</span>
                        This bank account will be available when creating a bank reconciliation.
                        Its accounting transactions will still be recorded through the
                        laboratory's Bank account in the Chart of Accounts.
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
                        Save Bank Account
                    </button>

                </div>

            </form>

        </div>
    </div>
</x-app-layout>