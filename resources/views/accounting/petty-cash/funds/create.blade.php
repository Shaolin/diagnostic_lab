```blade
<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-slate-100 leading-tight">
                Create Petty Cash Fund
            </h2>

            <p class="text-sm text-slate-400 mt-1">
                Set up a petty cash fund for a branch.
            </p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-slate-800 border border-slate-700 rounded-xl overflow-hidden">

                <div class="px-6 py-5 border-b border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-100">
                        Petty Cash Fund Details
                    </h3>

                    <p class="text-sm text-slate-400 mt-1">
                        Enter the details of the new petty cash fund.
                    </p>
                </div>

                <form method="POST"
                      action="{{ route('accounting.petty-cash.funds.store') }}"
                      class="p-6 space-y-6">

                    @csrf

                    {{-- Fund Name --}}
                    <div>
                        <label for="name"
                               class="block text-sm font-medium text-slate-300 mb-2">
                            Fund Name
                        </label>

                        <input type="text"
                               name="name"
                               id="name"
                               value="{{ old('name') }}"
                               placeholder="e.g. Main Petty Cash"
                               required
                               class="w-full rounded-lg bg-slate-900 border-slate-700 text-slate-100 placeholder-slate-500 focus:border-indigo-500 focus:ring-indigo-500">

                        @error('name')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Branch --}}
                    <div>
                        <label for="branch_id"
                               class="block text-sm font-medium text-slate-300 mb-2">
                            Branch
                        </label>

                        <select name="branch_id"
                                id="branch_id"
                                required
                                class="w-full rounded-lg bg-slate-900 border-slate-700 text-slate-100 focus:border-indigo-500 focus:ring-indigo-500">

                            <option value="">Select Branch</option>

                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}"
                                    {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach

                        </select>

                        @error('branch_id')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Custodian --}}
                    <div>
                        <label for="custodian_id"
                               class="block text-sm font-medium text-slate-300 mb-2">
                            Custodian
                        </label>

                        <select name="custodian_id"
                                id="custodian_id"
                                required
                                class="w-full rounded-lg bg-slate-900 border-slate-700 text-slate-100 focus:border-indigo-500 focus:ring-indigo-500">

                            <option value="">Select Custodian</option>

                            @foreach ($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ old('custodian_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach

                        </select>

                        @error('custodian_id')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Opening Balance --}}
                    <div>
                        <label for="opening_balance"
                               class="block text-sm font-medium text-slate-300 mb-2">
                            Opening Balance
                        </label>

                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                ₦
                            </span>

                            <input type="number"
                                   name="opening_balance"
                                   id="opening_balance"
                                   value="{{ old('opening_balance', 0) }}"
                                   min="0"
                                   step="0.01"
                                   required
                                   class="w-full rounded-lg bg-slate-900 border-slate-700 text-slate-100 pl-8 placeholder-slate-500 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <p class="mt-1 text-xs text-slate-500">
                            The amount initially placed in this petty cash fund.
                        </p>

                        @error('opening_balance')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Buttons --}}
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-700">

                        <a href="{{ route('accounting.petty-cash.funds.index') }}"
                           class="inline-flex items-center px-4 py-2 rounded-lg border border-slate-600 text-sm font-medium text-slate-300 hover:bg-slate-700">
                            Cancel
                        </a>

                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 rounded-lg bg-indigo-600 text-sm font-semibold text-white hover:bg-indigo-500">
                            Create Fund
                        </button>

                    </div>

                </form>
            </div>

        </div>
    </div>
</x-app-layout>
```
