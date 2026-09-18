<x-app-layout>
    <div class="p-6 max-w-4xl mx-auto space-y-6">

        {{-- Header --}}
        <div>
            <div class="flex items-center gap-3">
                <a href="{{ route('accounting.fixed-assets.index') }}"
                   class="text-slate-400 hover:text-white">
                    ←
                </a>

                <h1 class="text-2xl font-bold text-white">
                    Add Fixed Asset
                </h1>
            </div>

            <p class="text-sm text-slate-400 mt-1">
                Record a new fixed asset owned by the laboratory.
            </p>
        </div>

        {{-- Form --}}
        <div class="rounded-xl bg-slate-800 border border-slate-700 p-6">

            <form method="POST"
                  action="{{ route('accounting.fixed-assets.store') }}"
                  class="space-y-6">

                @csrf

                {{-- Asset Information --}}
                <div>
                    <h2 class="text-lg font-semibold text-white mb-4">
                        Asset Information
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        {{-- Asset Name --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-300 mb-1">
                                Asset Name
                            </label>

                            <input type="text"
                                   name="name"
                                   value="{{ old('name') }}"
                                   placeholder="e.g. Chemistry Analyzer"
                                   required
                                   class="w-full rounded-lg bg-slate-900 border-slate-600 text-white placeholder-slate-500 focus:border-blue-500 focus:ring-blue-500">

                            @error('name')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Category --}}
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-1">
                                Category
                            </label>

                            <input type="text"
                                   name="category"
                                   value="{{ old('category') }}"
                                   placeholder="e.g. Laboratory Equipment"
                                   class="w-full rounded-lg bg-slate-900 border-slate-600 text-white placeholder-slate-500 focus:border-blue-500 focus:ring-blue-500">

                            @error('category')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Branch --}}
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-1">
                                Branch / Location
                            </label>

                            <select name="branch_id"
                                    class="w-full rounded-lg bg-slate-900 border-slate-600 text-white focus:border-blue-500 focus:ring-blue-500">

                                <option value="">
                                    Head Office
                                </option>

                                @foreach($branches as $branch)
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

                        {{-- Description --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-300 mb-1">
                                Description
                            </label>

                            <textarea name="description"
                                      rows="3"
                                      placeholder="Optional description of the asset"
                                      class="w-full rounded-lg bg-slate-900 border-slate-600 text-white placeholder-slate-500 focus:border-blue-500 focus:ring-blue-500">{{ old('description') }}</textarea>

                            @error('description')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- Acquisition --}}
                <div class="border-t border-slate-700 pt-6">

                    <h2 class="text-lg font-semibold text-white mb-4">
                        Acquisition Details
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        {{-- Acquisition Date --}}
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-1">
                                Acquisition Date
                            </label>

                            <input type="date"
                                   name="acquisition_date"
                                   value="{{ old('acquisition_date', now()->format('Y-m-d')) }}"
                                   required
                                   class="w-full rounded-lg bg-slate-900 border-slate-600 text-white focus:border-blue-500 focus:ring-blue-500">

                            @error('acquisition_date')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Acquisition Cost --}}
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-1">
                                Acquisition Cost
                            </label>

                            <input type="number"
                                   name="acquisition_cost"
                                   value="{{ old('acquisition_cost') }}"
                                   step="0.01"
                                   min="0.01"
                                   placeholder="0.00"
                                   required
                                   class="w-full rounded-lg bg-slate-900 border-slate-600 text-white placeholder-slate-500 focus:border-blue-500 focus:ring-blue-500">

                            @error('acquisition_cost')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                             {{-- Payment Method --}}
<div>
    <label class="block text-sm font-medium text-slate-300 mb-1">
        Payment Method
    </label>

    <select name="payment_method"
            required
            class="w-full rounded-lg bg-slate-900 border-slate-600 text-white focus:border-blue-500 focus:ring-blue-500">

        <option value="">Select Payment Method</option>

        <option value="Cash"
            {{ old('payment_method') === 'Cash' ? 'selected' : '' }}>
            Cash
        </option>

        <option value="Bank"
            {{ old('payment_method') === 'Bank' ? 'selected' : '' }}>
            Bank
        </option>

        <option value="Accounts Payable"
            {{ old('payment_method') === 'Accounts Payable' ? 'selected' : '' }}>
            Accounts Payable
        </option>

    </select>

    @error('payment_method')
        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
    @enderror
</div>

                        {{-- Useful Life --}}
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-1">
                                Useful Life (Years)
                            </label>

                            <input type="number"
                                   name="useful_life_years"
                                   value="{{ old('useful_life_years') }}"
                                   min="1"
                                   placeholder="e.g. 5"
                                   class="w-full rounded-lg bg-slate-900 border-slate-600 text-white placeholder-slate-500 focus:border-blue-500 focus:ring-blue-500">

                            @error('useful_life_years')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Depreciation Method --}}
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-1">
                                Depreciation Method
                            </label>

                            <select name="depreciation_method"
                                    class="w-full rounded-lg bg-slate-900 border-slate-600 text-white focus:border-blue-500 focus:ring-blue-500">

                                <option value="straight_line"
                                    {{ old('depreciation_method', 'straight_line') === 'straight_line' ? 'selected' : '' }}>
                                    Straight Line
                                </option>

                            </select>

                            @error('depreciation_method')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                </div>

                {{-- Note --}}
                <div class="rounded-lg bg-slate-900 border border-slate-700 p-4">
                    <p class="text-sm text-slate-400">
                        The asset's initial book value will be equal to its acquisition cost.
                        Depreciation will be recorded separately.
                    </p>
                </div>

                {{-- Buttons --}}
                <div class="flex items-center justify-end gap-3 pt-2">

                    <a href="{{ route('accounting.fixed-assets.index') }}"
                       class="rounded-lg bg-slate-700 px-5 py-2.5 text-sm font-semibold text-slate-200 hover:bg-slate-600">
                        Cancel
                    </a>

                    <button type="submit"
                            class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
                        Save Fixed Asset
                    </button>

                </div>

            </form>

        </div>

    </div>
</x-app-layout>