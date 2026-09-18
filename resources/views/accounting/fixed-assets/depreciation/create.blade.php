<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            Record Depreciation
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-slate-900 border border-slate-700 rounded-xl shadow-lg p-6">

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-white">
                        Record Asset Depreciation
                    </h3>

                    <p class="text-sm text-slate-400 mt-1">
                        Record depreciation for an existing fixed asset.
                    </p>
                </div>

                <form method="POST"
                      action="{{ route('accounting.fixed-assets.depreciation.store') }}"
                      class="space-y-6">

                    @csrf

                    {{-- Fixed Asset --}}
                    <div>
                        <label for="fixed_asset_id"
                               class="block text-sm font-medium text-slate-300 mb-2">
                            Fixed Asset
                        </label>

                        <select
                            name="fixed_asset_id"
                            id="fixed_asset_id"
                            required
                            class="w-full rounded-lg bg-slate-800 border-slate-600 text-white focus:border-blue-500 focus:ring-blue-500">

                            <option value="">Select Fixed Asset</option>

                            @foreach ($assets as $asset)
                                <option value="{{ $asset->id }}"
                                    {{ old('fixed_asset_id') == $asset->id ? 'selected' : '' }}>
                                    {{ $asset->name }}
                                    — Book Value: ₦{{ number_format($asset->current_book_value, 2) }}
                                </option>
                            @endforeach

                        </select>

                        @error('fixed_asset_id')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Amount --}}
                    <div>
                        <label for="amount"
                               class="block text-sm font-medium text-slate-300 mb-2">
                            Depreciation Amount
                        </label>

                        <input
                            type="number"
                            name="amount"
                            id="amount"
                            value="{{ old('amount') }}"
                            min="0.01"
                            step="0.01"
                            required
                            class="w-full rounded-lg bg-slate-800 border-slate-600 text-white placeholder-slate-500 focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Enter depreciation amount">

                        @error('amount')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Date --}}
                    <div>
                        <label for="depreciation_date"
                               class="block text-sm font-medium text-slate-300 mb-2">
                            Depreciation Date
                        </label>

                        <input
                            type="date"
                            name="depreciation_date"
                            id="depreciation_date"
                            value="{{ old('depreciation_date', now()->toDateString()) }}"
                            required
                            class="w-full rounded-lg bg-slate-800 border-slate-600 text-white focus:border-blue-500 focus:ring-blue-500">

                        @error('depreciation_date')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="description"
                               class="block text-sm font-medium text-slate-300 mb-2">
                            Description
                        </label>

                        <textarea
                            name="description"
                            id="description"
                            rows="3"
                            class="w-full rounded-lg bg-slate-800 border-slate-600 text-white placeholder-slate-500 focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Optional description">{{ old('description') }}</textarea>

                        @error('description')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Notice --}}
                    <div class="rounded-lg bg-slate-800 border border-slate-700 p-4">
                        <p class="text-sm text-slate-400">
                            Depreciation will be posted to the General Ledger as
                            <span class="text-white font-medium">
                                Depreciation Expense
                            </span>
                            and
                            <span class="text-white font-medium">
                                Accumulated Depreciation
                            </span>.
                        </p>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex items-center justify-end gap-3 pt-2">

                        <a href="{{ route('accounting.fixed-assets.index') }}"
                           class="px-4 py-2.5 rounded-lg bg-slate-700 text-slate-200 hover:bg-slate-600 transition">
                            Cancel
                        </a>

                        <button
                            type="submit"
                            class="px-5 py-2.5 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-500 transition">
                            Record Depreciation
                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>
</x-app-layout>