<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            Add Inventory Item
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-slate-800 shadow-sm rounded-lg p-6">

                <form method="POST"
                      action="{{ route('accounting.inventory.items.store') }}">

                    @csrf

                    <div class="space-y-6">

                        {{-- Item Name --}}
                        <div>
                            <label for="name"
                                   class="block text-sm font-medium text-slate-300">
                                Item Name
                            </label>

                            <input type="text"
                                   name="name"
                                   id="name"
                                   value="{{ old('name') }}"
                                   required
                                   placeholder="e.g. Laboratory Gloves"
                                   class="mt-1 block w-full bg-slate-700 border-slate-600 text-white rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">

                            @error('name')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Category --}}
                        <div>
                            <label for="category"
                                   class="block text-sm font-medium text-slate-300">
                                Category
                            </label>

                            <input type="text"
                                   name="category"
                                   id="category"
                                   value="{{ old('category') }}"
                                   placeholder="e.g. Laboratory Supplies"
                                   class="mt-1 block w-full bg-slate-700 border-slate-600 text-white rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">

                            @error('category')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Unit --}}
                        <div>
                            <label for="unit"
                                   class="block text-sm font-medium text-slate-300">
                                Unit
                            </label>

                            <input type="text"
                                   name="unit"
                                   id="unit"
                                   value="{{ old('unit') }}"
                                   required
                                   placeholder="e.g. Box, Pack, Piece, Bottle"
                                   class="mt-1 block w-full bg-slate-700 border-slate-600 text-white rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">

                            @error('unit')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Minimum Stock --}}
                        <div>
                            <label for="minimum_stock"
                                   class="block text-sm font-medium text-slate-300">
                                Minimum Stock
                            </label>

                            <input type="number"
                                   name="minimum_stock"
                                   id="minimum_stock"
                                   value="{{ old('minimum_stock', 0) }}"
                                   min="0"
                                   step="0.01"
                                   required
                                   class="mt-1 block w-full bg-slate-700 border-slate-600 text-white rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">

                            <p class="mt-1 text-xs text-slate-400">
                                The level at which this item should be considered low in stock.
                            </p>

                            @error('minimum_stock')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div>
                            <label for="description"
                                   class="block text-sm font-medium text-slate-300">
                                Description
                            </label>

                            <textarea name="description"
                                      id="description"
                                      rows="3"
                                      class="mt-1 block w-full bg-slate-700 border-slate-600 text-white rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                      placeholder="Optional description">{{ old('description') }}</textarea>

                            @error('description')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    <div class="mt-6 flex items-center justify-end gap-3">

                        <a href="{{ route('accounting.inventory.items.index') }}"
                           class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-200 rounded-lg text-sm">
                            Cancel
                        </a>

                        <button type="submit"
                                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium">
                            Save Item
                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>

</x-app-layout>