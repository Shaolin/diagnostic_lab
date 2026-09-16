<x-app-layout>
    <div class="p-6">
        <div class="max-w-3xl mx-auto">

            <div class="mb-6">
                <h1 class="text-2xl font-bold text-white">
                    Issue Inventory Stock
                </h1>
                <p class="text-slate-400 mt-1">
                    Record inventory issued or used by a branch.
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-6 rounded-lg bg-red-900/40 border border-red-700 p-4">
                    <ul class="text-red-300 text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('accounting.inventory.stock-issues.store') }}"
                  class="bg-slate-800 border border-slate-700 rounded-xl p-6 space-y-6">
                @csrf

                {{-- Branch --}}
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        Branch
                    </label>

                    <select name="branch_id"
                            required
                            class="w-full rounded-lg bg-slate-700 border-slate-600 text-white">
                        <option value="">Select branch</option>

                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}"
                                {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Inventory Item --}}
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        Inventory Item
                    </label>

                    <select name="inventory_item_id"
                            required
                            class="w-full rounded-lg bg-slate-700 border-slate-600 text-white">
                        <option value="">Select item</option>

                        @foreach ($items as $item)
                            <option value="{{ $item->id }}"
                                {{ old('inventory_item_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->name }} ({{ $item->unit }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Quantity --}}
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        Quantity
                    </label>

                    <input type="number"
                           name="quantity"
                           value="{{ old('quantity') }}"
                           step="0.01"
                           min="0.01"
                           required
                           class="w-full rounded-lg bg-slate-700 border-slate-600 text-white">
                </div>

                {{-- Description --}}
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        Description
                    </label>

                    <textarea name="description"
                              rows="3"
                              placeholder="e.g. Used for laboratory tests"
                              class="w-full rounded-lg bg-slate-700 border-slate-600 text-white">{{ old('description') }}</textarea>
                </div>

                {{-- Date --}}
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        Issue Date
                    </label>

                    <input type="date"
                           name="movement_date"
                           value="{{ old('movement_date', now()->toDateString()) }}"
                           required
                           class="w-full rounded-lg bg-slate-700 border-slate-600 text-white">
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('accounting.inventory.stocks.index') }}"
                       class="px-5 py-2.5 rounded-lg bg-slate-700 text-slate-300 hover:bg-slate-600">
                        Cancel
                    </a>

                    <button type="submit"
                            class="px-5 py-2.5 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                        Issue Stock
                    </button>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>